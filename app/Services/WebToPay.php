<?php

namespace App\Services;

use Exception;

/**
 * Contains static methods for most used scenarios.
 */
class WebToPay {

    /**
     * WebToPay Library version.
     */
    const VERSION = '1.6';

    /**
     * Server URL where all requests should go.
     */
    const PAY_URL = 'https://www.mokejimai.lt/pay/';
    
    /**
     * Server URL where all non-lithuanian language requests should go.
     */
    const PAYSERA_PAY_URL = 'https://www.paysera.com/pay/';

    /**
     * Server URL where we can get XML with payment method data.
     */
    const XML_URL = 'https://www.mokejimai.lt/new/api/paymentMethods/';

    /**
     * SMS answer url.
     */
    const SMS_ANSWER_URL = 'https://www.mokejimai.lt/psms/respond/';

    /**
     * Builds request data array.
     *
     * This method checks all given data and generates correct request data
     * array or raises WebToPayException on failure.
     *
     * Possible keys:
     * https://www.mokejimai.lt/makro_specifikacija.html
     *
     * @param  array $data Information about current payment request
     *
     * @return array
     *
     * @throws WebToPayException on data validation error
     */
    public static function buildRequest($data) {
        if (!isset($data['sign_password']) || !isset($data['projectid'])) {
            throw new WebToPayException('sign_password or projectid is not provided');
        }
        $password = $data['sign_password'];
        $projectId = $data['projectid'];
        unset($data['sign_password']);
        unset($data['projectid']);

        $factory = new WebToPay_Factory(array('projectId' => $projectId, 'password' => $password));
        $requestBuilder = $factory->getRequestBuilder();
        return $requestBuilder->buildRequest($data);
    }


    /**
     * Builds request and redirects user to payment window with generated request data
     *
     * Possible array keys are described here:
     * https://www.mokejimai.lt/makro_specifikacija.html
     *
     * @param  array   $data Information about current payment request.
     * @param  boolean $exit if true, exits after sending Location header; default false
     *
     * @throws WebToPayException on data validation error
     */
    public static function redirectToPayment($data, $exit = false) {
        if (!isset($data['sign_password']) || !isset($data['projectid'])) {
            throw new WebToPayException('sign_password or projectid is not provided');
        }
        $password = $data['sign_password'];
        $projectId = $data['projectid'];
        unset($data['sign_password']);
        unset($data['projectid']);

        $factory = new WebToPay_Factory(array('projectId' => $projectId, 'password' => $password));
        $url = $factory->getRequestBuilder()
            ->buildRequestUrlFromData($data);

        if (headers_sent()) {
            echo '<script type="text/javascript">window.location = "' . addslashes($url) . '";</script>';
        } else {
            header("Location: $url", true);
        }
        echo '<script type="text/javascript">window.location = "' . addslashes($url) . '";</script>';
        exit;
        printf(
            'Redirecting to <a href="%s">%s</a>. Please wait.',
            htmlentities($url, ENT_QUOTES, 'UTF-8'),
            htmlentities($url, ENT_QUOTES, 'UTF-8')
        );
        if ($exit) {
            exit();
        }
    }

    /**
     * Builds repeat request data array.
     *
     * This method checks all given data and generates correct request data
     * array or raises WebToPayException on failure.
     *
     * Method accepts single parameter $data of array type. All possible array
     * keys are described here:
     * https://www.mokejimai.lt/makro_specifikacija.html
     *
     * @param  array $data Information about current payment request
     *
     * @return array
     *
     * @throws WebToPayException on data validation error
     */
    public static function buildRepeatRequest($data) {
        if (!isset($data['sign_password']) || !isset($data['projectid']) || !isset($data['orderid'])) {
            throw new WebToPayException('sign_password, projectid or orderid is not provided');
        }
        $password = $data['sign_password'];
        $projectId = $data['projectid'];
        $orderId = $data['orderid'];

        $factory = new WebToPay_Factory(array('projectId' => $projectId, 'password' => $password));
        $requestBuilder = $factory->getRequestBuilder();
        return $requestBuilder->buildRepeatRequest($orderId);
    }

    /**
     * Returns payment url. Argument is same as lang parameter in request data
     *
     * @param  string $language
     * @return string $url
     */
    public static function getPaymentUrl($language = 'LIT') {
       return (in_array($language, array('lt', 'lit', 'LIT')))
           ? self::PAY_URL
           : self::PAYSERA_PAY_URL;
    }

    /**
     * Parses response from WebToPay server and validates signs.
     *
     * This function accepts both micro and macro responses.
     *
     * First parameter usualy should be $_GET array.
     *
     * Description about response can be found here:
     * makro: https://www.mokejimai.lt/makro_specifikacija.html
     * mikro: https://www.mokejimai.lt/mikro_mokejimu_specifikacija_SMS.html
     *
     * If response is not correct, WebToPayException will be raised.
     *
     * @param array $query    Response array
     * @param array $userData
     *
     * @return array
     *
     * @throws WebToPayException
     * @deprecated use validateAndParseData() and check status code yourself
     */
    public static function checkResponse($query, $userData = array()) {
        $projectId = isset($userData['projectid']) ? $userData['projectid'] : null;
        $password = isset($userData['sign_password']) ? $userData['sign_password'] : null;
        $logFile = isset($userData['log']) ? $userData['log'] : null;

        try {
            $data = self::validateAndParseData($query, $projectId, $password);
            if ($data['type'] == 'macro' && $data['status'] != 1) {
                throw new WebToPayException('Expected status code 1', WebToPayException::E_DEPRECATED_USAGE);
            }

            if ($logFile) {
                self::log('OK', http_build_query($data), $logFile);
            }
            return $data;

        } catch (WebToPayException $exception) {
        	if ($logFile && $exception->getCode() != WebToPayException::E_DEPRECATED_USAGE) {
                self::log('ERR', $exception . "\nQuery: " . http_build_query($query), $logFile);
            }
            throw $exception;
        }
    }

    /**
     * Parses request (query) data and validates its signature.
     *
     * @param array   $query        usually $_GET
     * @param integer $projectId
     * @param string  $password
     *
     * @return array
     *
     * @throws WebToPayException
     */
    public static function validateAndParseData(array $query, $projectId, $password) {
        $factory = new WebToPay_Factory(array('projectId' => $projectId, 'password' => $password));
        $validator = $factory->getCallbackValidator();
        $data = $validator->validateAndParseData($query);
        return $data;
    }

    /**
     * Sends SMS answer
     *
     * @param array $userData
     *
     * @throws WebToPayException
     * @throws WebToPay_Exception_Validation
     */
    public static function smsAnswer($userData) {
        if (!isset($userData['id']) || !isset($userData['msg']) || !isset($userData['sign_password'])) {
            throw new WebToPay_Exception_Validation('id, msg and sign_password are required');
        }

        $smsId = $userData['id'];
        $text = $userData['msg'];
        $password = $userData['sign_password'];
        $logFile = isset($userData['log']) ? $userData['log'] : null;

        try {

            $factory = new WebToPay_Factory(array('password' => $password));
            $factory->getSmsAnswerSender()->sendAnswer($smsId, $text);

            if ($logFile) {
                self::log('OK', 'SMS ANSWER ' . $smsId . ' ' . $text, $logFile);
            }

        } catch (WebToPayException $e) {
            if ($logFile) {
                self::log('ERR', 'SMS ANSWER ' . $e, $logFile);
            }
            throw $e;
        }

    }


    /**
     * Gets available payment methods for project. Gets methods min and max amounts in specified currency.
     *
     * @param integer $projectId
     * @param string  $currency
     *
     * @return WebToPay_PaymentMethodList
     *
     * @throws WebToPayException
     */
    public static function getPaymentMethodList($projectId, $currency = 'LTL') {
        $factory = new WebToPay_Factory(array('projectId' => $projectId));
        return $factory->getPaymentMethodListProvider()->getPaymentMethodList($currency);
    }

    /**
     * Logs to file. Just skips logging if file is not writeable
     *
     * @param string $type
     * @param string $msg
     * @param string $logfile
     */
    protected static function log($type, $msg, $logfile) {
        $fp = @fopen($logfile, 'a');
        if (!$fp) {
            return;
        }

        $logline = array(
            $type,
            isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '-',
            date('[Y-m-d H:i:s O]'),
            'v' . self::VERSION . ':',
            $msg
        );

        $logline = implode(' ', $logline)."\n";
        fwrite($fp, $logline);
        fclose($fp);

        // clear big log file
        if (filesize($logfile) > 1024 * 1024 * pi()) {
            copy($logfile, $logfile.'.old');
            unlink($logfile);
        }
    }
}


/**
 * Base exception class for all exceptions in this library
 */
class WebToPayException extends Exception {

    /**
     * Missing field.
     */
    const E_MISSING = 1;

    /**
     * Invalid field value.
     */
    const E_INVALID = 2;

    /**
     * Max length exceeded.
     */
    const E_MAXLEN = 3;

    /**
     * Regexp for field value doesn't match.
     */
    const E_REGEXP = 4;

    /**
     * Missing or invalid user given parameters.
     */
    const E_USER_PARAMS = 5;

    /**
     * Logging errors
     */
    const E_LOG = 6;

    /**
     * SMS answer errors
     */
    const E_SMS_ANSWER = 7;

    /**
     * Macro answer errors
     */
    const E_STATUS = 8;

    /**
     * Library errors - if this happens, bug-report should be sent; also you can check for newer version
     */
    const E_LIBRARY = 9;

    /**
     * Errors in remote service - it returns some invalid data
     */
    const E_SERVICE = 10;
    
    /**
     * Deprecated usage errors
     */
    const E_DEPRECATED_USAGE = 11;

    /**
     * @var string|boolean
     */
    protected $fieldName = false;

    /**
     * Sets field which failed
     *
     * @param string $fieldName
     */
    public function setField($fieldName) {
        $this->fieldName = $fieldName;
    }

    /**
     * Gets field which failed
     *
     * @return string|boolean false
     */
    public function getField() {
        return $this->fieldName;
    }
}

/**
 * Parses and validates callbacks
 */
class WebToPay_CallbackValidator {

    /**
     * @var WebToPay_Sign_SignCheckerInterface
     */
    protected $signer;

    /**
     * @var WebToPay_Util
     */
    protected $util;

    /**
     * @var integer
     */
    protected $projectId;

    /**
     * Constructs object
     *
     * @param integer                            $projectId
     * @param WebToPay_Sign_SignCheckerInterface $signer
     * @param WebToPay_Util                      $util
     */
    public function __construct($projectId, WebToPay_Sign_SignCheckerInterface $signer, WebToPay_Util $util) {
        $this->signer = $signer;
        $this->util = $util;
        $this->projectId = $projectId;
    }

    /**
     * Parses callback parameters from query parameters and checks if sign is correct.
     * Request has parameter "data", which is signed and holds all callback parameters
     *
     * @param array $requestData
     *
     * @return array Parsed callback parameters
     *
     * @throws WebToPayException
     * @throws WebToPay_Exception_Callback
     */
    public function validateAndParseData(array $requestData) {
        if (!$this->signer->checkSign($requestData)) {
            throw new WebToPay_Exception_Callback('Invalid sign parameters, check $_GET length limit');
        }

        if (!isset($requestData['data'])) {
            throw new WebToPay_Exception_Callback('"data" parameter not found');
        }
        $data = $requestData['data'];

        $queryString = $this->util->decodeSafeUrlBase64($data);
        $request = $this->util->parseHttpQuery($queryString);

        if (!isset($request['projectid'])) {
            throw new WebToPay_Exception_Callback(
                'Project ID not provided in callback',
                WebToPayException::E_INVALID
            );
        }

        if ((string) $request['projectid'] !== (string) $this->projectId) {
            throw new WebToPay_Exception_Callback(
                sprintf('Bad projectid: %s, should be: %s', $request['projectid'], $this->projectId),
                WebToPayException::E_INVALID
            );
        }

        if (!isset($request['type']) || !in_array($request['type'], array('micro', 'macro'))) {
            $micro = (
                isset($request['to'])
                && isset($request['from'])
                && isset($request['sms'])
            );
            $request['type'] = $micro ? 'micro' : 'macro';
        }

        return $request;
    }

    /**
     * Checks data to have all the same parameters provided in expected array
     *
     * @param array $data
     * @param array $expected
     *
     * @throws WebToPayException
     */
    public function checkExpectedFields(array $data, array $expected) {
        foreach ($expected as $key => $value) {
            $passedValue = isset($data[$key]) ? $data[$key] : null;
            if ($passedValue != $value) {
                throw new WebToPayException(
                    sprintf('Field %s is not as expected (expected %s, got %s)', $key, $value, $passedValue)
                );
            }
        }
    }
}

/**
 * Raised if configuration is incorrect
 */
class WebToPay_Exception_Configuration extends WebToPayException {

}


/**
 * Raised on validation error in passed data when building the request
 */
class WebToPay_Exception_Validation extends WebToPayException {

    public function __construct($message, $code = 0, $field = null, Exception $previousException = null) {
        parent::__construct($message, $code, $previousException);
        if ($field) {
            $this->setField($field);
        }
    }
}

/**
 * Raised on error in callback
 */
class WebToPay_Exception_Callback extends WebToPayException {

}

/**
 * Simple web client
 */
class WebToPay_WebClient {

    /**
     * Gets page contents by specified URI. Adds query data if provided to the URI
     * Ignores status code of the response and header fields
     *
     * @param string $uri
     * @param array  $queryData
     *
     * @return string
     *
     * @throws WebToPayException
     */
    public function get($uri, array $queryData = array()) {
        if (count($queryData) > 0) {
            $uri .= strpos($uri, '?') === false ? '?' : '&';
            $uri .= http_build_query($queryData);
        }
        $url = parse_url($uri);
        if ('https' == $url['scheme']) {
            $host = 'ssl://'.$url['host'];
            $port = 443;
        } else {
            $host = $url['host'];
            $port = 80;
        }

        $fp = fsockopen($host, $port, $errno, $errstr, 30);
        if (!$fp) {
            throw new WebToPayException(sprintf('Cannot connect to %s', $uri), WebToPayException::E_INVALID);
        }

        if(isset($url['query'])) {
            $data = $url['path'].'?'.$url['query'];
        } else {
            $data = $url['path'];
        }

        $out = "GET " . $data . " HTTP/1.0\r\n";
        $out .= "Host: ".$url['host']."\r\n";
        $out .= "Connection: Close\r\n\r\n";

        $content = '';

        fwrite($fp, $out);
        while (!feof($fp)) $content .= fgets($fp, 8192);
        fclose($fp);

        list($header, $content) = explode("\r\n\r\n", $content, 2);

        return trim($content);
    }
}

/**
 * Utility class
 */
class WebToPay_Util {

    /**
     * Decodes url-safe-base64 encoded string
     * Url-safe-base64 is same as base64, but + is replaced to - and / to _
     *
     * @param string $encodedText
     *
     * @return string
     */
    public function decodeSafeUrlBase64($encodedText) {
        return base64_decode(strtr($encodedText, array('-' => '+', '_' => '/')));
    }

    /**
     * Encodes string to url-safe-base64
     * Url-safe-base64 is same as base64, but + is replaced to - and / to _
     *
     * @param string $text
     *
     * @return string
     */
    public function encodeSafeUrlBase64($text) {
        return strtr(base64_encode($text), array('+' => '-', '/' => '_'));
    }

    /**
     * Parses HTTP query to array
     *
     * @param string $query
     *
     * @return array
     */
    public function parseHttpQuery($query) {
        $params = array();
        parse_str($query, $params);
        if (get_magic_quotes_gpc()) {
            $params = $this->stripSlashesRecursively($params);
        }
        return $params;
    }

    /**
     * Strips slashes recursively, so this method can be used on arrays with more than one level
     *
     * @param mixed $data
     *
     * @return mixed
     */
    protected function stripSlashesRecursively($data) {
        if (is_array($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[stripslashes($key)] = $this->stripSlashesRecursively($value);
            }
            return $result;
        } else {
            return stripslashes($data);
        }
    }
}


/**
 * Used to build a complete request URL.
 *
 * Class WebToPay_UrlBuilder
 */
class WebToPay_UrlBuilder {

    const PLACEHOLDER_KEY = '[domain]';

    /**
     * @var array
     */
    protected $configuration = array();

    /**
     * @var string
     */
    protected $environment;

    /**
     * @var array
     */
    protected $environmentSettings;

    /**
     * @param array $configuration
     * @param string $environment
     */
    function __construct($configuration, $environment)
    {
        $this->configuration = $configuration;
        $this->environment = $environment;
        $this->environmentSettings = $this->configuration['routes'][$this->environment];
    }

    /**
     * Builds a complete request URL based on the provided parameters
     *
     * @param $request
     * @param null $language
     * @return string
     */
    public function buildForRequest($request, $language = null) {
        return $this->createUrlFromRequestAndLanguage($request, $language);
    }

    /**
     * Builds a complete URL for payment list API
     *
     * @param int $projectId
     * @param string $currency
     * @return string
     */
    public function buildForPaymentsMethodList($projectId, $currency) {
        $routeWithNoDomain = $this->environmentSettings['paymentMethodList'];
        $route = str_replace(self::PLACEHOLDER_KEY, $this->getDefaultDomain(), $routeWithNoDomain);
        return $route . $projectId . '/currency:' . $currency;
    }

    /**
     * Builds a complete URL for Sms Answer
     *
     * @return string
     */
    public function buildForSmsAnswer() {
        $routeWithNoDomain = $this->environmentSettings['smsAnswer'];
        $route = str_replace(self::PLACEHOLDER_KEY, $this->getDefaultDomain(), $routeWithNoDomain);
        return $route;
    }

    /**
     * Build the url to the public key
     *
     * @return string
     */
    public function buildForPublicKey() {
        $routeWithNoDomain = $this->environmentSettings['publicKey'];
        $route = str_replace(self::PLACEHOLDER_KEY, $this->getDefaultDomain(), $routeWithNoDomain);
        return $route;
    }

    /**
     * Creates an URL from the request and data provided.
     *
     * @param array $request
     * @param array $language
     * @return string
     */
    protected function createUrlFromRequestAndLanguage($request, $language) {
        $url = $this->getPaymentUrl($language) . '?' . http_build_query($request);
        return preg_replace('/[\r\n]+/is', '', $url);
    }

    /**
     * Returns payment url. Argument is same as lang parameter in request data
     *
     * @param  string $language
     * @return string $url
     */
    protected function getPaymentUrl($language) {
        $routeWithNoDomain = $this->environmentSettings['payment'];
        $route = str_replace(self::PLACEHOLDER_KEY, $this->getDomainByLanguage($language), $routeWithNoDomain);
        return $route;
    }

    /**
     * Gets the domain by lang variable
     * lit -> mokejimai.lt
     * eng -> paysera
     * etc
     *
     * @param $language
     * @return string
     */
    protected function getDomainByLanguage($language) {
        if (isset($this->configuration['domains'][$language])) {
            return $this->configuration['domains'][$language];
        } else {
            $defaultLanguage = $this->configuration['defaultDomainLanguage'];
            return $this->configuration['domains'][$defaultLanguage];
        }
    }

    /**
     * Gets the domain for the default language
     *
     * @return string
     */
    protected function getDefaultDomain()
    {
        return $this->getDomainByLanguage($this->configuration['defaultDomainLanguage']);
    }
}


/**
 * Wrapper class to group payment methods. Each country can have several payment method groups, each of them
 * have one or more payment methods.
 */
class WebToPay_PaymentMethodGroup {
    /**
     * Some unique (in the scope of country) key for this group
     *
     * @var string
     */
    protected $groupKey;

    /**
     * Translations array for this group. Holds associative array of group title by country codes.
     *
     * @var array
     */
    protected $translations;

    /**
     * Holds actual payment methods
     *
     * @var WebToPay_PaymentMethod[]
     */
    protected $paymentMethods;

    /**
     * Default language for titles
     *
     * @var string
     */
    protected $defaultLanguage;

    /**
     * Constructs object
     *
     * @param string $groupKey
     * @param array  $translations
     * @param string $defaultLanguage
     */
    public function __construct($groupKey, array $translations = array(), $defaultLanguage = 'lt') {
        $this->groupKey = $groupKey;
        $this->translations = $translations;
        $this->defaultLanguage = $defaultLanguage;
        $this->paymentMethods = array();
    }

    /**
     * Sets default language for titles.
     * Returns itself for fluent interface
     *
     * @param string $language
     *
     * @return WebToPay_PaymentMethodGroup
     */
    public function setDefaultLanguage($language) {
        $this->defaultLanguage = $language;
        foreach ($this->paymentMethods as $paymentMethod) {
            $paymentMethod->setDefaultLanguage($language);
        }
        return $this;
    }

    /**
     * Gets default language for titles
     *
     * @return string
     */
    public function getDefaultLanguage() {
        return $this->defaultLanguage;
    }

    /**
     * Gets title of the group. Tries to get title in specified language. If it is not found or if language is not
     * specified, uses default language, given to constructor.
     *
     * @param string [Optional] $languageCode
     *
     * @return string
     */
    public function getTitle($languageCode = null) {
        if ($languageCode !== null && isset($this->translations[$languageCode])) {
            return $this->translations[$languageCode];
        } elseif (isset($this->translations[$this->defaultLanguage])) {
            return $this->translations[$this->defaultLanguage];
        } else {
            return $this->groupKey;
        }
    }

    /**
     * Returns group key
     *
     * @return string
     */
    public function getKey() {
        return $this->groupKey;
    }

    /**
     * Returns available payment methods for this group
     *
     * @return WebToPay_PaymentMethod[]
     */
    public function getPaymentMethods() {
        return $this->paymentMethods;
    }


    /**
     * Adds new payment method for this group.
     * If some other payment method with specified key was registered earlier, overwrites it.
     * Returns given payment method
     *
     * @param WebToPay_PaymentMethod $paymentMethod
     *
     * @return WebToPay_PaymentMethod
     */
    public function addPaymentMethod(WebToPay_PaymentMethod $paymentMethod) {
        return $this->paymentMethods[$paymentMethod->getKey()] = $paymentMethod;
    }

    /**
     * Gets payment method object with key. If no payment method with such key is found, returns null.
     *
     * @param string $key
     *
     * @return null|WebToPay_PaymentMethod
     */
    public function getPaymentMethod($key) {
        return isset($this->paymentMethods[$key]) ? $this->paymentMethods[$key] : null;
    }

    /**
     * Returns new group instance with only those payment methods, which are available for provided amount.
     *
     * @param integer $amount
     * @param string  $currency
     *
     * @return WebToPay_PaymentMethodGroup
     */
    public function filterForAmount($amount, $currency) {
        $group = new WebToPay_PaymentMethodGroup($this->groupKey, $this->translations, $this->defaultLanguage);
        foreach ($this->getPaymentMethods() as $paymentMethod) {
            if ($paymentMethod->isAvailableForAmount($amount, $currency)) {
                $group->addPaymentMethod($paymentMethod);
            }
        }
        return $group;
    }

    /**
     * Returns new country instance with only those payment methods, which are returns or not iban number after payment
     *
     * @param boolean $isIban
     *
     * @return WebToPay_PaymentMethodGroup
     */
    public function filterForIban($isIban = true) {
        $group = new WebToPay_PaymentMethodGroup($this->groupKey, $this->translations, $this->defaultLanguage);
        foreach ($this->getPaymentMethods() as $paymentMethod) {
            if ($paymentMethod->isIban() == $isIban) {
                $group->addPaymentMethod($paymentMethod);
            }
        }
        return $group;
    }

    /**
     * Returns whether this group has no payment methods
     *
     * @return boolean
     */
    public function isEmpty() {
        return count($this->paymentMethods) === 0;
    }

    /**
     * Loads payment methods from given XML node
     *
     * @param SimpleXMLElement $groupNode
     */
    public function fromXmlNode($groupNode) {
        foreach ($groupNode->payment_type as $paymentTypeNode) {
            $key = (string) $paymentTypeNode->attributes()->key;
            $titleTranslations = array();
            foreach ($paymentTypeNode->title as $titleNode) {
                $titleTranslations[(string) $titleNode->attributes()->language] = (string) $titleNode;
            }
            $logoTranslations = array();
            foreach ($paymentTypeNode->logo_url as $logoNode) {
                if ((string) $logoNode !== '') {
                    $logoTranslations[(string) $logoNode->attributes()->language] = (string) $logoNode;
                }
            }
            $minAmount = null;
            $maxAmount = null;
            $currency = null;
            $isIban = false;
            $baseCurrency = null;
            if (isset($paymentTypeNode->min)) {
                $minAmount = (int) $paymentTypeNode->min->attributes()->amount;
                $currency = (string) $paymentTypeNode->min->attributes()->currency;
            }
            if (isset($paymentTypeNode->max)) {
                $maxAmount = (int) $paymentTypeNode->max->attributes()->amount;
                $currency = (string) $paymentTypeNode->max->attributes()->currency;
            }

            if (isset($paymentTypeNode->is_iban)) {
                $isIban = (int) $paymentTypeNode->is_iban;
            }
            if (isset($paymentTypeNode->base_currency)) {
                $baseCurrency = (string) $paymentTypeNode->base_currency;
            }
            $this->addPaymentMethod($this->createPaymentMethod(
                $key, $minAmount, $maxAmount, $currency, $logoTranslations, $titleTranslations, $isIban, $baseCurrency
            ));
        }
    }

    /**
     * Method to create new payment method instances. Overwrite if you have to use some other subclass.
     *
     * @param string $key
     * @param integer $minAmount
     * @param integer $maxAmount
     * @param string $currency
     * @param array $logoList
     * @param array $titleTranslations
     * @param bool $isIban
     * @param null $baseCurrency
     *
     * @return WebToPay_PaymentMethod
     */
    protected function createPaymentMethod(
        $key, $minAmount, $maxAmount, $currency, array $logoList = array(), array $titleTranslations = array(),
        $isIban = false, $baseCurrency = null
    ) {
        return new WebToPay_PaymentMethod(
            $key, $minAmount, $maxAmount, $currency, $logoList, $titleTranslations, $this->defaultLanguage,
            $isIban, $baseCurrency
        );
    }
}

/**
 * Sends answer to SMS payment if it was not provided with response to callback
 */
class WebToPay_SmsAnswerSender {

    /**
     * @var string
     */
    protected $password;

    /**
     * @var WebToPay_WebClient
     */
    protected $webClient;

    /**
     * @var WebToPay_UrlBuilder $urlBuilder
     */
    protected $urlBuilder;

    /**
     * Constructs object
     *
     * @param string             $password
     * @param WebToPay_WebClient $webClient
     * @param WebToPay_UrlBuilder $urlBuilder
     */
    public function __construct(
        $password,
        WebToPay_WebClient $webClient,
        WebToPay_UrlBuilder $urlBuilder
    ) {
        $this->password = $password;
        $this->webClient = $webClient;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Sends answer by sms ID get from callback. Answer can be send only if it was not provided
     * when responding to callback
     *
     * @param integer $smsId
     * @param string  $text
     *
     * @throws WebToPayException
     */
    public function sendAnswer($smsId, $text) {
        $content = $this->webClient->get($this->urlBuilder->buildForSmsAnswer(), array(
            'id' => $smsId,
            'msg' => $text,
            'transaction' => md5($this->password . '|' . $smsId),
        ));
        if (strpos($content, 'OK') !== 0) {
            throw new WebToPayException(
                sprintf('Error: %s', $content),
                WebToPayException::E_SMS_ANSWER
            );
        }
    }
}


/**
 * Class to hold information about payment method
 */
class WebToPay_PaymentMethod {
    /**
     * Assigned key for this payment method
     *
     * @var string
     */
    protected $key;

    /**
     * Logo url list by language. Usually logo is same for all languages, but exceptions exist
     *
     * @var array
     */
    protected $logoList;

    /**
     * Title list by language
     *
     * @var array
     */
    protected $titleTranslations;

    /**
     * Default language to use for titles
     *
     * @var string
     */
    protected $defaultLanguage;

    /**
     * @var boolean
     */
    protected $isIban;

    /**
     * @var string
     */
    protected $baseCurrency;

    /**
     * Constructs object
     *
     * @param string  $key
     * @param integer $minAmount
     * @param integer $maxAmount
     * @param string  $currency
     * @param array   $logoList
     * @param array   $titleTranslations
     * @param string  $defaultLanguage
     * @param bool    $isIban
     * @param string  $baseCurrency
     */
    public function __construct(
        $key, $minAmount, $maxAmount, $currency, array $logoList = array(), array $titleTranslations = array(),
        $defaultLanguage = 'lt', $isIban = false, $baseCurrency = null
    ) {
        $this->key = $key;
        $this->minAmount = $minAmount;
        $this->maxAmount = $maxAmount;
        $this->currency = $currency;
        $this->logoList = $logoList;
        $this->titleTranslations = $titleTranslations;
        $this->defaultLanguage = $defaultLanguage;
        $this->isIban = $isIban;
        $this->baseCurrency = $baseCurrency;
    }

    /**
     * Sets default language for titles.
     * Returns itself for fluent interface
     *
     * @param string $language
     *
     * @return WebToPay_PaymentMethod
     */
    public function setDefaultLanguage($language) {
        $this->defaultLanguage = $language;
        return $this;
    }

    /**
     * Gets default language for titles
     *
     * @return string
     */
    public function getDefaultLanguage() {
        return $this->defaultLanguage;
    }

    /**
     * Get assigned payment method key
     *
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * Gets logo url for this payment method. Uses specified language or default one.
     * If logotype is not found for specified language, null is returned.
     *
     * @param string [Optional] $languageCode
     *
     * @return string|null
     */
    public function getLogoUrl($languageCode = null) {
        if ($languageCode !== null && isset($this->logoList[$languageCode])) {
            return $this->logoList[$languageCode];
        } elseif (isset($this->logoList[$this->defaultLanguage])) {
            return $this->logoList[$this->defaultLanguage];
        } else {
            return null;
        }
    }

    /**
     * Gets title for this payment method. Uses specified language or default one.
     *
     * @param string [Optional] $languageCode
     *
     * @return string
     */
    public function getTitle($languageCode = null) {
        if ($languageCode !== null && isset($this->titleTranslations[$languageCode])) {
            return $this->titleTranslations[$languageCode];
        } elseif (isset($this->titleTranslations[$this->defaultLanguage])) {
            return $this->titleTranslations[$this->defaultLanguage];
        } else {
            return $this->key;
        }
    }

    /**
     * Checks if this payment method can be used for specified amount.
     * Throws exception if currency checked is not the one, for which payment method list was downloaded.
     *
     * @param integer $amount
     * @param string  $currency
     *
     * @return boolean
     *
     * @throws WebToPayException
     */
    public function isAvailableForAmount($amount, $currency) {
        if ($this->currency !== $currency) {
            throw new WebToPayException(
                'Currencies does not match. You have to get payment types for the currency you are checking. Given currency: '
                    . $currency . ', available currency: ' . $this->currency
            );
        }
        return (
            ($this->minAmount === null || $amount >= $this->minAmount)
            && ($this->maxAmount === null || $amount <= $this->maxAmount)
        );
    }

    /**
     * Returns min amount for this payment method. If no min amount is specified, returns empty string.
     *
     * @return string
     */
    public function getMinAmountAsString() {
        return $this->minAmount === null ? '' : ($this->minAmount . ' ' . $this->currency);
    }

    /**
     * Returns max amount for this payment method. If no max amount is specified, returns empty string.
     *
     * @return string
     */
    public function getMaxAmountAsString() {
        return $this->maxAmount === null ? '' : ($this->maxAmount . ' ' . $this->currency);
    }

    /**
     * Set if this method returns IBAN number after payment
     *
     * @param boolean $isIban
     */
    public function setIsIban($isIban) {
        $this->isIban = $isIban == 1;
    }

    /**
     * Get if this method returns IBAN number after payment
     *
     * @return bool
     */
    public function isIban() {
        return $this->isIban;
    }

    /**
     * Setter of BaseCurrency
     *
     * @param string $baseCurrency
     */
    public function setBaseCurrency($baseCurrency)
    {
        $this->baseCurrency = $baseCurrency;
    }

    /**
     * Getter of BaseCurrency
     *
     * @return string
     */
    public function getBaseCurrency()
    {
        return $this->baseCurrency;
    }
}


/**
 * Class with all information about available payment methods for some project, optionally filtered by some amount.
 */
class WebToPay_PaymentMethodList {
    /**
     * Holds available payment countries
     *
     * @var WebToPay_PaymentMethodCountry[]
     */
    protected $countries;

    /**
     * Default language for titles
     *
     * @var string
     */
    protected $defaultLanguage;

    /**
     * Project ID, to which this method list is valid
     *
     * @var integer
     */
    protected $projectId;

    /**
     * Currency for min and max amounts in this list
     *
     * @var string
     */
    protected $currency;

    /**
     * If this list is filtered for some amount, this field defines it
     *
     * @var integer
     */
    protected $amount;

    /**
     * Constructs object
     *
     * @param integer $projectId
     * @param string  $currency              currency for min and max amounts in this list
     * @param string  $defaultLanguage
     * @param integer $amount                null if this list is not filtered by amount
     */
    public function __construct($projectId, $currency, $defaultLanguage = 'lt', $amount = null) {
        $this->projectId = $projectId;
        $this->countries = array();
        $this->defaultLanguage = $defaultLanguage;
        $this->currency = $currency;
        $this->amount = $amount;
    }

    /**
     * Sets default language for titles.
     * Returns itself for fluent interface
     *
     * @param string $language
     *
     * @return WebToPay_PaymentMethodList
     */
    public function setDefaultLanguage($language) {
        $this->defaultLanguage = $language;
        foreach ($this->countries as $country) {
            $country->setDefaultLanguage($language);
        }
        return $this;
    }

    /**
     * Gets default language for titles
     *
     * @return string
     */
    public function getDefaultLanguage() {
        return $this->defaultLanguage;
    }

    /**
     * Gets project ID for this payment method list
     *
     * @return integer
     */
    public function getProjectId() {
        return $this->projectId;
    }

    /**
     * Gets currency for min and max amounts in this list
     *
     * @return string
     */
    public function getCurrency() {
        return $this->currency;
    }

    /**
     * Gets whether this list is already filtered for some amount
     *
     * @return boolean
     */
    public function isFiltered() {
        return $this->amount !== null;
    }

    /**
     * Returns available countries
     *
     * @return WebToPay_PaymentMethodCountry[]
     */
    public function getCountries() {
        return $this->countries;
    }

    /**
     * Adds new country to payment methods. If some other country with same code was registered earlier, overwrites it.
     * Returns added country instance
     *
     * @param WebToPay_PaymentMethodCountry $country
     *
     * @return WebToPay_PaymentMethodCountry
     */
    public function addCountry(WebToPay_PaymentMethodCountry $country) {
        return $this->countries[$country->getCode()] = $country;
    }

    /**
     * Gets country object with specified country code. If no country with such country code is found, returns null.
     *
     * @param string $countryCode
     *
     * @return null|WebToPay_PaymentMethodCountry
     */
    public function getCountry($countryCode) {
        return isset($this->countries[$countryCode]) ? $this->countries[$countryCode] : null;
    }

    /**
     * Returns new payment method list instance with only those payment methods, which are available for provided
     * amount.
     * Returns itself, if list is already filtered and filter amount matches the given one.
     *
     * @param integer $amount
     * @param string  $currency
     *
     * @return WebToPay_PaymentMethodList
     *
     * @throws WebToPayException    if this list is already filtered and not for provided amount
     */
    public function filterForAmount($amount, $currency) {
        if ($currency !== $this->currency) {
            throw new WebToPayException(
                'Currencies do not match. Given currency: ' . $currency . ', currency in list: ' . $this->currency
            );
        }
        if ($this->isFiltered()) {
            if ($this->amount === $amount) {
                return $this;
            } else {
                throw new WebToPayException('This list is already filtered, use unfiltered list instead');
            }
        } else {
            $list = new WebToPay_PaymentMethodList($this->projectId, $currency, $this->defaultLanguage, $amount);
            foreach ($this->getCountries() as $country) {
                $country = $country->filterForAmount($amount, $currency);
                if (!$country->isEmpty()) {
                    $list->addCountry($country);
                }
            }
            return $list;
        }
    }

    /**
     * Loads countries from given XML node
     *
     * @param SimpleXMLElement $xmlNode
     */
    public function fromXmlNode($xmlNode) {
        foreach ($xmlNode->country as $countryNode) {
            $titleTranslations = array();
            foreach ($countryNode->title as $titleNode) {
                $titleTranslations[(string) $titleNode->attributes()->language] = (string) $titleNode;
            }
            $this->addCountry($this->createCountry((string) $countryNode->attributes()->code, $titleTranslations))
                ->fromXmlNode($countryNode);
        }
    }

    /**
     * Method to create new country instances. Overwrite if you have to use some other country subtype.
     *
     * @param string $countryCode
     * @param array  $titleTranslations
     *
     * @return WebToPay_PaymentMethodCountry
     */
    protected function createCountry($countryCode, array $titleTranslations = array()) {
        return new WebToPay_PaymentMethodCountry($countryCode, $titleTranslations, $this->defaultLanguage);
    }
}

/**
 * Builds and signs requests
 */
class WebToPay_RequestBuilder {

    /**
     * @var string
     */
    protected $projectPassword;

    /**
     * @var WebToPay_Util
     */
    protected $util;

    /**
     * @var integer
     */
    protected $projectId;


    /**
     * @var WebToPay_UrlBuilder $urlBuilder
     */
    protected $urlBuilder;

    /**
     * Constructs object
     *
     * @param integer       $projectId
     * @param string        $projectPassword
     * @param WebToPay_Util $util
     * @param WebToPay_UrlBuilder $urlBuilder
     */
    public function __construct(
        $projectId,
        $projectPassword,
        WebToPay_Util $util,
        WebToPay_UrlBuilder $urlBuilder
    )
    {
        $this->projectId = $projectId;
        $this->projectPassword = $projectPassword;
        $this->util = $util;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Builds request data array.
     *
     * This method checks all given data and generates correct request data
     * array or raises WebToPayException on failure.
     *
     * @param  array $data information about current payment request
     *
     * @return array
     *
     * @throws WebToPayException
     */
    public function buildRequest($data) {
        $this->validateRequest($data, self::getRequestSpec());
        $data['version'] = WebToPay::VERSION;
        $data['projectid'] = $this->projectId;
        unset($data['repeat_request']);
        return $this->createRequest($data);
    }

    /**
     * Builds the full request url (including the protocol and the domain)
     *
     * @param array $data
     * @return string
     */
    public function buildRequestUrlFromData($data) {
        $language = isset($data['lang']) ? $data['lang'] : null;
        $request = $this->buildRequest($data);
        return $this->urlBuilder->buildForRequest($request, $language);
    }

    /**
     * Builds repeat request data array.
     *
     * This method checks all given data and generates correct request data
     * array or raises WebToPayException on failure.
     *
     * @param string $orderId order id of repeated request
     *
     * @return array
     *
     * @throws WebToPayException
     */
    public function buildRepeatRequest($orderId) {
        $data['orderid'] = $orderId;
        $data['version'] = WebToPay::VERSION;
        $data['projectid'] = $this->projectId;
        $data['repeat_request'] = '1';
        return $this->createRequest($data);
    }

    /**
     * Builds the full request url for a repeated request (including the protocol and the domain)
     *
     * @param string $orderId order id of repeated request
     * @return string
     */
    public function buildRepeatRequestUrlFromOrderId($orderId) {
        $request = $this->buildRepeatRequest($orderId);
        return $this->urlBuilder->buildForRequest($request);
    }

    /**
     * Checks data to be valid by passed specification
     *
     * @param array $data
     * @param array $specs
     *
     * @throws WebToPay_Exception_Validation
     */
    protected function validateRequest($data, $specs) {
        foreach ($specs as $spec) {
            list($name, $maxlen, $required, $regexp) = $spec;
            if ($required && !isset($data[$name])) {
                throw new WebToPay_Exception_Validation(
                    sprintf("'%s' is required but missing.", $name),
                    WebToPayException::E_MISSING,
                    $name
                );
            }

            if (!empty($data[$name])) {
                if ($maxlen && strlen($data[$name]) > $maxlen) {
                    throw new WebToPay_Exception_Validation(sprintf(
                        "'%s' value is too long (%d), %d characters allowed.",
                        $name,
                        strlen($data[$name]),
                        $maxlen
                    ), WebToPayException::E_MAXLEN, $name);
                }

                if ($regexp !== ''  && !preg_match($regexp, $data[$name])) {
                    throw new WebToPay_Exception_Validation(
                        sprintf("'%s' value '%s' is invalid.", $name, $data[$name]),
                        WebToPayException::E_REGEXP,
                        $name
                    );
                }
            }
        }
    }

    /**
     * Makes request data array from parameters, also generates signature
     *
     * @param array $request
     *
     * @return array
     */
    protected function createRequest(array $request) {
        $data = $this->util->encodeSafeUrlBase64(http_build_query($request));
        return array(
            'data' => $data,
            'sign' => md5($data . $this->projectPassword),
        );
    }

    /**
     * Returns specification of fields for request.
     *
     * Array structure:
     *   name      â€“ request item name
     *   maxlen    â€“ max allowed value for item
     *   required  â€“ is this item is required
     *   regexp    â€“ regexp to test item value
     *
     * @return array
     */
    protected static function getRequestSpec() {
        return array(
            array('orderid',       40,  true,  ''),
            array('accepturl',     255, true,  ''),
            array('cancelurl',     255, true,  ''),
            array('callbackurl',   255, true,  ''),
            array('lang',          3,   false, '/^[a-z]{3}$/i'),
            array('amount',        11,  false, '/^\d+$/'),
            array('currency',      3,   false, '/^[a-z]{3}$/i'),
            array('payment',       20,  false, ''),
            array('country',       2,   false, '/^[a-z_]{2}$/i'),
            array('paytext',       255, false, ''),
            array('p_firstname',   255, false, ''),
            array('p_lastname',    255, false, ''),
            array('p_email',       255, false, ''),
            array('p_street',      255, false, ''),
            array('p_city',        255, false, ''),
            array('p_state',       20,  false, ''),
            array('p_zip',         20,  false, ''),
            array('p_countrycode', 2,   false, '/^[a-z]{2}$/i'),
            array('test',          1,   false, '/^[01]$/'),
            array('time_limit',    19,  false, '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/'),
        );
    }
}

/**
 * Checks SS2 signature. Depends on SSL functions
 */
class WebToPay_Sign_SS2SignChecker implements WebToPay_Sign_SignCheckerInterface {

    /**
     * @var string
     */
    protected $publicKey;

    /**
     * @var WebToPay_Util
     */
    protected $util;

    /**
     * Constructs object
     *
     * @param string        $publicKey
     * @param WebToPay_Util $util
     */
    public function __construct($publicKey, WebToPay_Util $util) {
        $this->publicKey = $publicKey;
        $this->util = $util;
    }

    /**
     * Checks signature
     *
     * @param array $request
     *
     * @return boolean
     *
     * @throws WebToPay_Exception_Callback
     */
    public function checkSign(array $request) {
        if (!isset($request['data']) || !isset($request['ss2'])) {
            throw new WebToPay_Exception_Callback('Not enough parameters in callback. Possible version mismatch');
        }

        $ss2 = $this->util->decodeSafeUrlBase64($request['ss2']);
        $ok = openssl_verify($request['data'], $ss2, $this->publicKey);
        return $ok === 1;
    }
}

/**
 * Sign checker which checks SS1 signature. SS1 does not depend on SSL functions
 */
class WebToPay_Sign_SS1SignChecker implements WebToPay_Sign_SignCheckerInterface {

    /**
     * @var string
     */
    protected $projectPassword;

    /**
     * Constructs object
     *
     * @param string $projectPassword
     */
    public function __construct($projectPassword) {
        $this->projectPassword = $projectPassword;
    }

    /**
     * Check for SS1, which is not depend on openssl functions.
     *
     * @param array $request
     *
     * @return boolean
     *
     * @throws WebToPay_Exception_Callback
     */
    public function checkSign(array $request) {
        if (!isset($request['data']) || !isset($request['ss1'])) {
            throw new WebToPay_Exception_Callback('Not enough parameters in callback. Possible version mismatch');
        }

        return md5($request['data'] . $this->projectPassword) === $request['ss1'];
    }
}

/**
 * Interface for sign checker
 */
interface WebToPay_Sign_SignCheckerInterface {

    /**
     * Checks whether request is signed properly
     *
     * @param array $request
     *
     * @return boolean
     */
    public function checkSign(array $request);
}

/**
 * Loads data about payment methods and constructs payment method list object from that data
 * You need SimpleXML support to use this feature
 */
class WebToPay_PaymentMethodListProvider {

    /**
     * @var integer
     */
    protected $projectId;

    /**
     * @var WebToPay_WebClient
     */
    protected $webClient;

    /**
     * Holds constructed method lists by currency
     *
     * @var WebToPay_PaymentMethodList[]
     */
    protected $methodListCache = array();

    /**
     * Builds various request URLs
     *
     * @var WebToPay_UrlBuilder $urlBuilder
     */
    protected $urlBuilder;

    /**
     * Constructs object
     *
     * @param integer            $projectId
     * @param WebToPay_WebClient $webClient
     * @param WebToPay_UrlBuilder $urlBuilder
     *
     * @throws WebToPayException if SimpleXML is not available
     */
    public function __construct(
        $projectId,
        WebToPay_WebClient $webClient,
        WebToPay_UrlBuilder $urlBuilder
    )
    {
        $this->projectId = $projectId;
        $this->webClient = $webClient;
        $this->urlBuilder = $urlBuilder;

        if (!function_exists('simplexml_load_string')) {
            throw new WebToPayException('You have to install libxml to use payment methods API');
        }
    }

    /**
     * Gets payment method list for specified currency
     *
     * @param string $currency
     *
     * @return WebToPay_PaymentMethodList
     *
     * @throws WebToPayException
     */
    public function getPaymentMethodList($currency) {
        if (!isset($this->methodListCache[$currency])) {
            $xmlAsString = $this->webClient->get($this->urlBuilder->buildForPaymentsMethodList($this->projectId, $currency));
            $useInternalErrors = libxml_use_internal_errors(false);
            $rootNode = simplexml_load_string($xmlAsString);
            libxml_clear_errors();
            libxml_use_internal_errors($useInternalErrors);
            if (!$rootNode) {
                throw new WebToPayException('Unable to load XML from remote server');
            }
            $methodList = new WebToPay_PaymentMethodList($this->projectId, $currency);
            $methodList->fromXmlNode($rootNode);
            $this->methodListCache[$currency] = $methodList;
        }
        return $this->methodListCache[$currency];
    }
}

/**
 * Payment method configuration for some country
 */
class WebToPay_PaymentMethodCountry {
    /**
     * @var string
     */
    protected $countryCode;

    /**
     * Holds available payment types for this country
     *
     * @var WebToPay_PaymentMethodGroup[]
     */
    protected $groups;

    /**
     * Default language for titles
     *
     * @var string
     */
    protected $defaultLanguage;

    /**
     * Translations array for this country. Holds associative array of country title by language codes.
     *
     * @var array
     */
    protected $titleTranslations;

    /**
     * Constructs object
     *
     * @param string $countryCode
     * @param array  $titleTranslations
     * @param string $defaultLanguage
     */
    public function __construct($countryCode, $titleTranslations, $defaultLanguage = 'lt') {
        $this->countryCode = $countryCode;
        $this->defaultLanguage = $defaultLanguage;
        $this->titleTranslations = $titleTranslations;
        $this->groups = array();
    }

    /**
     * Sets default language for titles.
     * Returns itself for fluent interface
     *
     * @param string $language
     *
     * @return WebToPay_PaymentMethodCountry
     */
    public function setDefaultLanguage($language) {
        $this->defaultLanguage = $language;
        foreach ($this->groups as $group) {
            $group->setDefaultLanguage($language);
        }
        return $this;
    }

    /**
     * Gets title of the group. Tries to get title in specified language. If it is not found or if language is not
     * specified, uses default language, given to constructor.
     *
     * @param string [Optional] $languageCode
     *
     * @return string
     */
    public function getTitle($languageCode = null) {
        if ($languageCode !== null && isset($this->titleTranslations[$languageCode])) {
            return $this->titleTranslations[$languageCode];
        } elseif (isset($this->titleTranslations[$this->defaultLanguage])) {
            return $this->titleTranslations[$this->defaultLanguage];
        } else {
            return $this->countryCode;
        }
    }

    /**
     * Gets default language for titles
     *
     * @return string
     */
    public function getDefaultLanguage() {
        return $this->defaultLanguage;
    }

    /**
     * Gets country code
     *
     * @return string
     */
    public function getCode() {
        return $this->countryCode;
    }

    /**
     * Adds new group to payment methods for this country.
     * If some other group was registered earlier with same key, overwrites it.
     * Returns given group
     *
     * @param WebToPay_PaymentMethodGroup $group
     *
     * @return WebToPay_PaymentMethodGroup
     */
    public function addGroup(WebToPay_PaymentMethodGroup $group) {
        return $this->groups[$group->getKey()] = $group;
    }

    /**
     * Gets group object with specified group key. If no group with such key is found, returns null.
     *
     * @param string $groupKey
     *
     * @return null|WebToPay_PaymentMethodGroup
     */
    public function getGroup($groupKey) {
        return isset($this->groups[$groupKey]) ? $this->groups[$groupKey] : null;
    }

    /**
     * Returns payment method groups registered for this country.
     *
     * @return WebToPay_PaymentMethodGroup[]
     */
    public function getGroups() {
        return $this->groups;
    }

    /**
     * Gets payment methods in all groups
     *
     * @return WebToPay_PaymentMethod[]
     */
    public function getPaymentMethods() {
        $paymentMethods = array();
        foreach ($this->groups as $group) {
            $paymentMethods = array_merge($paymentMethods, $group->getPaymentMethods());
        }
        return $paymentMethods;
    }

    /**
     * Returns new country instance with only those payment methods, which are available for provided amount.
     *
     * @param integer $amount
     * @param string  $currency
     *
     * @return WebToPay_PaymentMethodCountry
     */
    public function filterForAmount($amount, $currency) {
        $country = new WebToPay_PaymentMethodCountry($this->countryCode, $this->titleTranslations, $this->defaultLanguage);
        foreach ($this->getGroups() as $group) {
            $group = $group->filterForAmount($amount, $currency);
            if (!$group->isEmpty()) {
                $country->addGroup($group);
            }
        }
        return $country;
    }

    /**
     * Returns new country instance with only those payment methods, which are returns or not iban number after payment
     *
     * @param boolean $isIban
     *
     * @return WebToPay_PaymentMethodCountry
     */
    public function filterForIban($isIban = true) {
        $country = new WebToPay_PaymentMethodCountry($this->countryCode, $this->titleTranslations, $this->defaultLanguage);
        foreach ($this->getGroups() as $group) {
            $group = $group->filterForIban($isIban);
            if (!$group->isEmpty()) {
                $country->addGroup($group);
            }
        }
        return $country;
    }

    /**
     * Returns whether this country has no groups
     *
     * @return boolean
     */
    public function isEmpty() {
        return count($this->groups) === 0;
    }

    /**
     * Loads groups from given XML node
     *
     * @param SimpleXMLElement $countryNode
     */
    public function fromXmlNode($countryNode) {
        foreach ($countryNode->payment_group as $groupNode) {
            $key = (string) $groupNode->attributes()->key;
            $titleTranslations = array();
            foreach ($groupNode->title as $titleNode) {
                $titleTranslations[(string) $titleNode->attributes()->language] = (string) $titleNode;
            }
            $this->addGroup($this->createGroup($key, $titleTranslations))->fromXmlNode($groupNode);
        }
    }

    /**
     * Method to create new group instances. Overwrite if you have to use some other group subtype.
     *
     * @param string $groupKey
     * @param array  $translations
     *
     * @return WebToPay_PaymentMethodGroup
     */
    protected function createGroup($groupKey, array $translations = array()) {
        return new WebToPay_PaymentMethodGroup($groupKey, $translations, $this->defaultLanguage);
    }
}

/**
 * Creates objects. Also caches to avoid creating several instances of same objects
 */
class WebToPay_Factory {

    const ENV_PRODUCTION = 'production';
    const ENV_SANDBOX = 'sandbox';

    /**
     * @var array
     */
    protected static $defaultConfiguration = array(
        'routes' => array(
            self::ENV_PRODUCTION => array(
                'publicKey'           => 'http://downloads.[domain]/download/public.key',
                'payment'             => 'https://www.[domain]/pay/',
                'paymentMethodList'   => 'https://www.[domain]/new/api/paymentMethods/',
                'smsAnswer'           => 'https://www.[domain]/psms/respond/',
            ),
            self::ENV_SANDBOX => array(
                'publicKey'         => 'http://downloads-sandbox.[domain]/download/public.key',
                'payment'           => 'https://sandbox.[domain]/pay/',
                'paymentMethodList' => 'https://sandbox.[domain]/new/api/paymentMethods/',
                'smsAnswer'         => 'https://sandbox.[domain]/psms/respond/',
            ),
        ),

        'domains' => array(
            'lit' => 'mokejimai.lt',
            'eng' => 'paysera.com',
        ),

        'defaultDomainLanguage' => 'lit'
    );

    /**
     * @var string
     */
    protected $environment;

    /**
     * @var array
     */
    protected $configuration;

    /**
     * @var WebToPay_WebClient
     */
    protected $webClient = null;

    /**
     * @var WebToPay_CallbackValidator
     */
    protected $callbackValidator = null;

    /**
     * @var WebToPay_RequestBuilder
     */
    protected $requestBuilder = null;

    /**
     * @var WebToPay_Sign_SignCheckerInterface
     */
    protected $signer = null;

    /**
     * @var WebToPay_SmsAnswerSender
     */
    protected $smsAnswerSender = null;

    /**
     * @var WebToPay_PaymentMethodListProvider
     */
    protected $paymentMethodListProvider = null;

    /**
     * @var WebToPay_Util
     */
    protected $util = null;

    /**
     * @var WebToPay_UrlBuilder
     */
    protected $urlBuilder = null;


    /**
     * Constructs object.
     * Configuration keys: projectId, password
     * They are required only when some object being created needs them,
     *     if they are not found at that moment - exception is thrown
     *
     * @param array $configuration
     */
    public function __construct(array $configuration = array()) {

        $this->configuration = array_merge(self::$defaultConfiguration, $configuration);
        $this->environment = self::ENV_PRODUCTION;
    }

    /**
     * If passed true the factory will use sandbox when constructing URLs
     *
     * @param $enableSandbox
     * @return self
     */
    public function useSandbox($enableSandbox)
    {
        if ($enableSandbox) {
            $this->environment = self::ENV_SANDBOX;
        } else {
            $this->environment = self::ENV_PRODUCTION;
        }
        return $this;
    }

    /**
     * Creates or gets callback validator instance
     *
     * @return WebToPay_CallbackValidator
     *
     * @throws WebToPay_Exception_Configuration
     */
    public function getCallbackValidator() {
        if ($this->callbackValidator === null) {
            if (!isset($this->configuration['projectId'])) {
                throw new WebToPay_Exception_Configuration('You have to provide project ID');
            }
            $this->callbackValidator = new WebToPay_CallbackValidator(
                $this->configuration['projectId'],
                $this->getSigner(),
                $this->getUtil()
            );
        }
        return $this->callbackValidator;
    }

    /**
     * Creates or gets request builder instance
     *
     * @throws WebToPay_Exception_Configuration
     *
     * @return WebToPay_RequestBuilder
     */
    public function getRequestBuilder() {
        if ($this->requestBuilder === null) {
            if (!isset($this->configuration['password'])) {
                throw new WebToPay_Exception_Configuration('You have to provide project password to sign request');
            }
            if (!isset($this->configuration['projectId'])) {
                throw new WebToPay_Exception_Configuration('You have to provide project ID');
            }
            $this->requestBuilder = new WebToPay_RequestBuilder(
                $this->configuration['projectId'],
                $this->configuration['password'],
                $this->getUtil(),
                $this->getUrlBuilder()
            );
        }
        return $this->requestBuilder;
    }

    /**
     * @return WebToPay_UrlBuilder
     */
    public function getUrlBuilder() {
        if ($this->urlBuilder === null) {
            $this->urlBuilder = new WebToPay_UrlBuilder(
                $this->configuration,
                $this->environment
            );
        }
        return $this->urlBuilder;
    }

    /**
     * Creates or gets SMS answer sender instance
     *
     * @throws WebToPay_Exception_Configuration
     *
     * @return WebToPay_SmsAnswerSender
     */
    public function getSmsAnswerSender() {
        if ($this->smsAnswerSender === null) {
            if (!isset($this->configuration['password'])) {
                throw new WebToPay_Exception_Configuration('You have to provide project password');
            }
            $this->smsAnswerSender = new WebToPay_SmsAnswerSender(
                $this->configuration['password'],
                $this->getWebClient(),
                $this->getUrlBuilder()
            );
        }
        return $this->smsAnswerSender;
    }

    /**
     * Creates or gets payment list provider instance
     *
     * @throws WebToPay_Exception_Configuration
     *
     * @return WebToPay_PaymentMethodListProvider
     */
    public function getPaymentMethodListProvider() {
        if ($this->paymentMethodListProvider === null) {
            if (!isset($this->configuration['projectId'])) {
                throw new WebToPay_Exception_Configuration('You have to provide project ID');
            }
            $this->paymentMethodListProvider = new WebToPay_PaymentMethodListProvider(
                $this->configuration['projectId'],
                $this->getWebClient(),
                $this->getUrlBuilder()

            );
        }
        return $this->paymentMethodListProvider;
    }

    /**
     * Creates or gets signer instance. Chooses SS2 signer if openssl functions are available, SS1 in other case
     *
     * @throws WebToPay_Exception_Configuration
     *
     * @return WebToPay_Sign_SignCheckerInterface
     *
     * @throws WebToPayException
     */
    protected function getSigner() {
        if ($this->signer === null) {
            if (function_exists('openssl_pkey_get_public')) {
                $webClient = $this->getWebClient();
                $publicKey = $webClient->get($this->getUrlBuilder()->buildForPublicKey());
                if (!$publicKey) {
                    throw new WebToPayException('Cannot download public key from WebToPay website');
                }
                $this->signer = new WebToPay_Sign_SS2SignChecker($publicKey, $this->getUtil());
            } else {
                if (!isset($this->configuration['password'])) {
                    throw new WebToPay_Exception_Configuration(
                        'You have to provide project password if OpenSSL is unavailable'
                    );
                }
                $this->signer = new WebToPay_Sign_SS1SignChecker($this->configuration['password']);
            }
        }
        return $this->signer;
    }

    /**
     * Creates or gets web client instance
     *
     * @throws WebToPay_Exception_Configuration
     *
     * @return WebToPay_WebClient
     */
    protected function getWebClient() {
        if ($this->webClient === null) {
            $this->webClient = new WebToPay_WebClient();
        }
        return $this->webClient;
    }

    /**
     * Creates or gets util instance
     *
     * @throws WebToPay_Exception_Configuration
     *
     * @return WebToPay_Util
     */
    protected function getUtil() {
        if ($this->util === null) {
            $this->util = new WebToPay_Util();
        }
        return $this->util;
    }
}