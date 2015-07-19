<?php
if ( ! function_exists( 'add_action' ) ) exit;
require_once SRInit::$path.'/includes/SRAutoloader.php';
SRAutoloader::init();
class SRRequestApi {
    public $options;
    protected $status;
    protected $email;
    protected $affiliate_key;
    protected $api_key;
    protected $api_url;
    protected $lang;
    public function  __construct(){
        $this->options = get_option(SRInit::$option_name);
        if(empty($this->options)){
            // Ошибка настройки не заданны
            $this->status = false;
        }elseif( ! isset( $this->options['config']['email'] ) ||
            empty( $this->options['config']['email'] )){
            // Ошибка Email не задан
            $this->status = false;
        }elseif( ! isset( $this->options['config']['affiliate_key'] ) ||
            empty( $this->options['config']['affiliate_key'] )){
            // Ошибка Email не задан
            $this->status = false;
        }elseif( ! isset( $this->options['config']['api_key'] ) ||
            empty( $this->options['config']['api_key'] )){
            // Ошибка api key не задан
            $this->status = false;
        }else{
            $this->status = true;
            $this->email = $this->options['config']['email'];
            $this->affiliate_key = $this->options['config']['affiliate_key'];
            $this->api_key = $this->options['config']['api_key'];
            $this->lang = $this->options['config']['local'];
            $this->api_url = rtrim('http://api.excursiopedia.com/v1', '/' );
        }
    }
    /*********************************************************
     * @param array $args
     * Activities (Деятельность )
     * *******************************************************
     * GET api.excursiopedia.com/v1/activities
     * [Экспериментально] Возвращает массив деятельности.
     * Params:
     * ***
     * username (required)
     * Электронная почта адрес пользователя, которые используют API
     * Значение: Должен быть строка
     * ***
     * api_key (required)
     * Ключ API пользовательского
     * Значение: Должен быть строка
     * ***
     * lang (required)
     * Код языка: RU, EN, FR, и т.д.
     * Значение: Должен быть строка
     * ***
     * page (optional)
     * Номер страницы: 1,2,3 ... N. Каждая страница содержит 1000 записей.
     * Значение: Должно быть целое
     * ***
     * limit (optional)
     * Желаемый товаров: 10,20, 30..N (МАКС 1000).
     * Значение: Должно быть целое
     * ***
     * geo (optional)
     * Гео-фильтр ПАРАМЕТРЫ
     * Значение: Должен быть Хэш
     * ***
     * geo[position] (optional)
     * Координаты
     * Значение: Должен быть Хэш
     * ***
     * geo[position][lat] (optional)
     * Широта
     * Значение: Должен быть Float
     * ***
     * geo[position][lon] (optional)
     * Долгота
     * Значение: Должен быть Float
     * ***
     * geo[distance] (optional)
     * Поиск радиус в метрах
     * Значение: Должно быть целое int
     * ***
     * query (optional)
     * Фильтр по ключевым словам
     * Значение: Должен быть строка
     * ***
     * dates (optional)
     * Фильтр по имеющимся даты
     * Значение: Должен быть одним из: Dates.
     * ***
     * popularity_level (optional)
     * Фильтр по уровню популярности
     * Значение: Должен быть одним из: hidden, authentic, most.
     * ***
     * duration (optional)
     * Фильтр по продолжительности
     * Значение: Должен быть одним из:  hour, several_hours, half_day, day, several_days.
     * ***
     * fitness_level (optional)
     * Фильтр по фитнес-уровня
     * Значение: Должен быть одним из: easy, normal, hard, danger, extreme.
     * ***
     * travellers (optional)
     * Фильтр по членству Travelers Group
     * Значение: Должен быть Хэш
     */
    public function getActivities($args = array() ){
        if(!isset($this->status)) return false;
        $defaults = array('page' => false, 'limit' => false,
            'geo' => false, 'geo_position' => false, 'geo_lat' => false,
            'geo_lon' => false, 'geo_distance' => false, 'query' => false,
            'dates' => false, 'popularity_level' => false, 'duration' => false,
            'fitness_level' => false, 'travellers' => false);
        /**
         * wp_parse_args()
         * спомогательная функция, объединяет два массива, так что
         * параметры первого массива (передаваемые) заменяют при
         * совпадении параметры второго массива (по-умолчанию).
         * В первый аргумент можно передать строку с параметрами.
         */
        extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );
        $page = ( false !== $page ) ? "&page={$page}" : "";
        $limit = ( false !== $limit ) ? "&limit={$limit}" : "";
        $query = ( false !== $query ) ? "&query={$query}" : "";
        $dates = ( false !== $dates ) ? "&dates={$dates}" : "";
        $popularity_level = ( false !== $popularity_level ) ? "&popularity_level={$popularity_level}" : "";
        $duration = ( false !== $duration ) ? "&duration={$duration}" : "";
        $fitness_level = ( false !== $fitness_level ) ? "&fitness_level={$fitness_level}" : "";
        $travellers = ( false !== $travellers ) ? "&travellers={$travellers}" : "";
        $args_string = $page.$limit.$query.$dates.$popularity_level.$duration.
            $fitness_level.$travellers;
        $param = "?api_key=$this->api_key&lang=$this->lang&username=$this->email{$args_string}";
        $request_string = "$this->api_url/activities$param";
        //return $request_string;
        return $this->request($request_string);
    }
    /*********************************************************
     * @param array $args
     * Activities (Деятельность )
     * GET api.excursiopedia.com/v1/activities/:id
     * [Экспериментально] Возвращает расширенную информацию
     * о данной деятельности, указанный ID
     * * * Params:
     * username required
     * An E-mail address of a user who use API
     * Value: Must be String
     * ***
     * api_key required
     * An API key of a user
     * Value: Must be String
     * ***
     * lang required
     * A language code: ru, en, fr, etc.
     * Value: Must be String
     * ***
     */
    public function getActivitiesID($args = array() ){
        if(!isset($this->status)) return false;
        $defaults = array('id' => false);
        extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );
        if( false === $id ) return false;
        $param = "{$id}?api_key=$this->api_key&lang=$this->lang&username=$this->email";
        $request_string = "$this->api_url/activities/$param";
        //return $request_string;
        return $this->request($request_string);

    }
    /**********************************************************
     * @param array $args
     * Categories(Категории)
     * ********************************************************
     * GET api.excursiopedia.com/v1/cities/:city_id/categories
     * Возвращает массив категорий с подкатегориями и продуктов
     * внутри. ! Не все города имеют категории
     * ********************************************************
     * * Params:
     * username required
     * An E-mail address of a user who use API
     * Value: Must be String
     * ***
     * api_key required
     * An API key of a user
     * Value: Must be String
     * ***
     * lang required
     * A language code: ru, en, fr, etc.
     * Value: Must be String
     * ***
     * city_id optional
     * city id to request categories from
     * Value: Must be Integer
     */
    public function getCategories($args = array() ){
        if(!isset($this->status)) return false;
        $defaults = array('id' => false);
        extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );
        if( false === $id ) return false;
        $param = "{$id}/categories?api_key=$this->api_key&lang=$this->lang&username=$this->email";
        $request_string = "$this->api_url/cities/$param";
        //return $request_string;
        return $this->request($request_string);
    }
    /**********************************************************
     * @param array $args
     * Cities(Города )
     * ********************************************************
     * GET api.excursiopedia.com/v1/cities
     * Возвращает массив городов
     * ********************************************************
     * Params:
     * username required
     * An E-mail address of a user who use API
     * Value: Must be String
     * ***
     * api_key required
     * An API key of a user
     * Value: Must be String
     * ***
     * lang required
     * A language code: ru, en, fr, etc.
     * Value: Must be String
     * ***
     * page optional
     * A page number: 1,2,3…N. Each page contains 1000 records.
     * Value: Must be Integer
     * ***
     * limit optional
     * Desired number of items: 10,20, 30..N(MAX 1000).
     * Value: Must be Integer
     * ***
     * name optional
     * City name to search
     * Value: Must be String
     */
    public function getCities($args = array() ){
        if(!isset($this->status)) return false;
        $defaults = array('page' => false, 'limit' => false,
            'name' => false);
        extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );
        $page = ( false !== $page ) ? "&page={$page}" : "";
        $limit = ( false !== $limit ) ? "&limit={$limit}" : "";
        $name = ( false !== $name ) ? "&name={$name}" : "";
        $param = "?api_key=$this->api_key&lang=$this->lang&username=$this->email{$page}{$limit}{$name}";
        $request_string = "$this->api_url/cities$param";
        //return $request_string;
        return $this->request($request_string);
    }
    /**********************************************************
     * @param array $args
     * Cities(Города )
     * GET api.excursiopedia.com/v1/cities/:id
     * Возвращает расширенную информацию о данном городе,
     * определяется ID
     * * Params:
     * username required
     * An E-mail address of a user who use API
     * Value: Must be String
     * ***
     * api_key required
     * An API key of a user
     * Value: Must be String
     * ***
     * lang required
     * A language code: ru, en, fr, etc.
     * Value: Must be String
     * ***
     * city_id optional
     * city id to request categories from
     * Value: Must be Integer
     */
    public function getCitiesID($args = array() ){
        if(!isset($this->status)) return false;
        $defaults = array('id' => false);
        extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );
        if( false === $id ) return false;
        $param = "{$id}?api_key=$this->api_key&lang=$this->lang&username=$this->email";
        $request_string = "$this->api_url/cities/$param";
        //return $request_string;
        return $this->request($request_string);
    }
    /**********************************************************
     * @param array $args
     * Countries(Страны)
     * ********************************************************
     * GET api.excursiopedia.com/v1/countries
     * Возвращает массив стран
     * ********************************************************
     * * Params:
     * username required
     * An E-mail address of a user who use API
     * Value: Must be String
     * ***
     * api_key required
     * An API key of a user
     * Value: Must be String
     * ***
     * lang required
     * A language code: ru, en, fr, etc.
     * Value: Must be String
     *
     */
    public function getCountries($args = array() ){
        if(!isset($this->status)) return false;
        $param = "?api_key=$this->api_key&lang=$this->lang&username=$this->email";
        $request_string = "$this->api_url/countries$param";
        //return $request_string;
        return $this->request($request_string);
    }
    /**********************************************************
     * @param array $args
     * Countries(Страны)
     ** GET api.excursiopedia.com/v1/countries/:id
     * Возвращает расширенную информацию о той или иной стране,
     * определяется ID
     * * * Params:
     * username required
     * An E-mail address of a user who use API
     * Value: Must be String
     * ***
     * api_key required
     * An API key of a user
     * Value: Must be String
     * ***
     * lang required
     * A language code: ru, en, fr, etc.
     * Value: Must be String
     * ***
     * id required
     * Country numeric identifier
     * Value: Must be Integer
     */
    public function getCountriesID($args = array() ){
        if(!isset($this->status)) return false;
        $defaults = array('id' => false);
        extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );
        if( false === $id ) return false;
        $param = "{$id}?api_key=$this->api_key&lang=$this->lang&username=$this->email";
        $request_string = "$this->api_url/countries/$param";
        //return $request_string;
        return $this->request($request_string);
    }
    /**********************************************************
     * @param array $args
     * Discussions(Обсуждения )
     * ********************************************************
     * GET api.excursiopedia.com/v1/discussions
     * Возвращает массив обсуждения указанного пользователя
     * * * Params:
     * username required
     * An E-mail address of a user who use API
     * Value: Must be String
     * ***
     * api_key required
     * An API key of a user
     * Value: Must be String
     * ***
     * lang required
     * A language code: ru, en, fr, etc.
     * Value: Must be String
     *

     */
    public function getDiscussions($args = array() ){
        if(!isset($this->status)) return false;
        $param = "?api_key=$this->api_key&lang=$this->lang&username=$this->email";
        $request_string = "$this->api_url/discussions$param";
        //return $request_string;
        return $this->request($request_string);
    }
    /**********************************************************
     * @param array $args
     * Discussions(Обсуждения )
     * * ********************************************************
     * GET api.excursiopedia.com/v1/discussions/:id
     * Возвращает расширенную информацию о данном обсуждении,
     * указанный ID пользователя и
     * * * * Params:
     * username required
     * An E-mail address of a user who use API
     * Value: Must be String
     * ***
     * api_key required
     * An API key of a user
     * Value: Must be String
     * ***
     * lang required
     * A language code: ru, en, fr, etc.
     * Value: Must be String
     */
    public function getDiscussionsID($args = array() ){
        if(!isset($this->status)) return false;
        $defaults = array('id' => false);
        extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );
        if( false === $id ) return false;
        $param = "{$id}?api_key=$this->api_key&lang=$this->lang&username=$this->email";
        $request_string = "$this->api_url/discussions/$param";
        //return $request_string;
        return $this->request($request_string);
    }
    /**********************************************************
     * @param array $args
     * Orders(Заказы )
     * ********************************************************
     * GET api.excursiopedia.com/v1/orders/:id
     * Возвращает расширенную информацию о заданном порядке,
     * предусмотренных ID
     * ********************************************************
     ** * * * Params:
     * username required
     * An E-mail address of a user who use API
     * Value: Must be String
     * ***
     * api_key required
     * An API key of a user
     * Value: Must be String
     * ***
     * lang required
     * A language code: ru, en, fr, etc.
     * Value: Must be String
     * ***
     * id required
     * An ID of an order.
     * Value: Must be Integer
     */
    public function getOrders($args = array() ){
        if(!isset($this->status)) return false;
        $defaults = array('id' => false);
        extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );
        if( false === $id ) return false;
        $param = "{$id}?api_key=$this->api_key&lang=$this->lang&username=$this->email";
        $request_string = "$this->api_url/orders/$param";
        //return $request_string;
        return $this->request($request_string);
    }
    /**********************************************************
     * @param array $args
     * Product categories(Категории продукта )
     * ********************************************************
     * GET api.excursiopedia.com/v1/product_categories
     * Возвращает массив категорий с подкатегориями
     * ********************************************************
     *** * * * Params:
     * username required
     * An E-mail address of a user who use API
     * Value: Must be String
     * ***
     * api_key required
     * An API key of a user
     * Value: Must be String
     * ***
     * lang required
     * A language code: ru, en, fr, etc.
     * Value: Must be String
     * ***
     */
    public function getProductCategories($args = array() ){
        if(!isset($this->status)) return false;
        $param = "?api_key=$this->api_key&lang=$this->lang&username=$this->email";
        $request_string = "$this->api_url/product_categories$param";
        //return $request_string;
        return $this->request($request_string);
    }
    /**********************************************************
     * @param array $args
     * Products(Продукты )
     * ********************************************************
     * GET api.excursiopedia.com/v1/products
     * Возвращает массив продуктов.
     * *** * * * Params:
     * username required
     * An E-mail address of a user who use API
     * Value: Must be String
     * ***
     * api_key required
     * An API key of a user
     * Value: Must be String
     * ***
     * lang required
     * A language code: ru, en, fr, etc.
     * Value: Must be String
     * ***
     * page optional
     * A page number: 1,2,3…N. Each page contains 1000 records.
     * Value: Must be Integer
     * ***
     * limit optional
     * Desired number of items: 10,20, 30..N(MAX 1000).
     * Value: Must be Integer
     * ***
     * currency optional
     * Currency code.
     * Value: Must be String
     * ***
     * type optional
     * You can specify the type of products.
     * At the moment we have Shared tours (shared_tour)
     * and Group tours (private_tour).
     * Value: Must be one of: private_tour, shared_tour.
     * ***
     * booking_type optional
     * Filters products by a booking type. For example,
     * we can choose products which are available for instant booking.
     * Value: Must be one of: instant, pre_booked.
     * ***
     * provider_id optional
     * An ID of a provider for whom to return results for.
     * Value: Must be Integer
     * ***
     * region_id optional
     * Filters results by a region.
     * Value: Must be Integer
     * ***
     * country_id optional
     * Filters results by a country.
     * Value: Must be Integer
     * ***
     * city_id optional
     * Filters results by a city.
     * Value: Must be Integer
     * ***
     * updated_at optional
     * Returns results which we updated after specified date.
     * Value: Must be Date
     * ***
     * sort_by optional
     * Returns products with rating more than 6 or unrated, sorted by rating
     * Value: Must be one of: popular.
     * ***
     * fallback optional
     * Works only for city. city_id and limit parameters have to be set.
     * If products count for current city less than limit, it returns
     * products for current region and country.
     * Value: Must be one of: true.
     * ***
     * price_from optional
     * Filter by minimal price for products, in EUR
     * Value: Must be Integer
     * ***
     * price_to optional
     * Filter by maximal price for products, in EUR
     * Value: Must be Integer
     * ***
     * category_ids optional
     * Single or multiple category ids
     * Value: Must be one of: Integer.
     */
    public function getProducts($args = array() ){
        if(!isset($this->status)) return false;
        $defaults = array('page' => false, 'limit' => false,
            'currency' => false, 'type' => false, 'booking_type' => false,
            'provider_id' => false, 'region_id' => false, 'country_id' => false,
            'city_id' => false, 'updated_at' => false, 'sort_by' => false,
            'fallback' => false, 'price_from' => false, 'price_to' => false,
            'category_ids' => false);
        extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );
        $page = ( false !== $page ) ? "&page={$page}" : "";
        $limit = ( false !== $limit ) ? "&limit={$limit}" : "";
        $currency = ( false !== $currency ) ? "&currency={$currency}" : "";
        $type = ( false !== $type ) ? "&type={$type}" : "";
        $booking_type = ( false !== $booking_type ) ? "&booking_type={$booking_type}" : "";
        $provider_id  = ( false !== $provider_id  ) ? "&provider_id={$provider_id}" : "";
        $region_id  = ( false !== $region_id  ) ? "&region_id={$region_id}" : "";
        $country_id  = ( false !== $country_id  ) ? "&country_id={$country_id}" : "";
        $city_id  = ( false !== $city_id  ) ? "&city_id={$city_id}" : "";
        $updated_at  = ( false !== $updated_at  ) ? "&updated_at={$updated_at}" : "";
        $sort_by  = ( false !== $sort_by  ) ? "&sort_by={$sort_by}" : "";
        $fallback  = ( false !== $fallback  ) ? "&fallback={$fallback}" : "";
        $price_from  = ( false !== $price_from  ) ? "&price_from={$price_from}" : "";
        $price_to  = ( false !== $price_to  ) ? "&price_to={$price_to}" : "";
        $category_ids  = ( false !== $category_ids  ) ? "&category_ids={$category_ids}" : "";
        $args_string = $page.$limit.$currency.$type.$booking_type.$provider_id.
                       $region_id.$country_id.$city_id.$updated_at.$sort_by.
                       $fallback.$price_from.$price_to.$category_ids;
        $param = "?api_key=$this->api_key&lang=$this->lang&username=$this->email{$args_string}";
        $request_string = "$this->api_url/products$param";
        //return $request_string;
        return $this->request($request_string);
    }
    /**********************************************************
     * @param array $args
     * Products(Продукты )
     * * ********************************************************
     * GET api.excursiopedia.com/v1/products/:id
     * Возвращает расширенную информацию о данном продукте,
     * указанный ID
     * *** * * * Params:
     * username required
     * An E-mail address of a user who use API
     * Value: Must be String
     * ***
     * api_key required
     * An API key of a user
     * Value: Must be String
     * ***
     * lang required
     * A language code: ru, en, fr, etc.
     * Value: Must be String
     * ***
     * id required
     * An ID of a product.
     * Value: Must be Integer
     */
    public function getProductsID($args = array() ){
        if(!isset($this->status)) return false;
        $defaults = array('id' => false);
        extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );
        if( false === $id ) return false;
        $param = "{$id}?api_key=$this->api_key&lang=$this->lang&username=$this->email";
        $request_string = "$this->api_url/products/$param";
        //return $request_string;
        return $this->request($request_string);
    }
    /**********************************************************
     * @param array $args
     * Regions(Районы )
     * ********************************************************
     * GET api.excursiopedia.com/v1/regions
     * Список регионов
     * * *** * * * Params:
     * username required
     * An E-mail address of a user who use API
     * Value: Must be String
     * ***
     * api_key required
     * An API key of a user
     * Value: Must be String
     * ***
     * lang required
     * A language code: ru, en, fr, etc.
     * Value: Must be String
     * ***
     */
    public function getRegions($args = array() ){
        if(!isset($this->status)) return false;
        $param = "?api_key=$this->api_key&lang=$this->lang&username=$this->email";
        $request_string = "$this->api_url/regions$param";
        //return $request_string;
        return $this->request($request_string);
    }
    /**********************************************************
     * @param array $args
     * Regions(Районы )
     * ********************************************************
     * GET api.excursiopedia.com/v1/regions/:id
     * Возвращает расширенную информацию о данном регионе, указано ID
     * * *** * * * Params:
     * username required
     * An E-mail address of a user who use API
     * Value: Must be String
     * ***
     * api_key required
     * An API key of a user
     * Value: Must be String
     * ***
     * lang required
     * A language code: ru, en, fr, etc.
     * Value: Must be String
     * ***
     * id required
     * Returns extended information of a given region, specified by ID
     * Value: Must be Integer
     */
    public function getRegionsID($args = array() ){
        if(!isset($this->status)) return false;
        $defaults = array('id' => false);
        extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );
        if( false === $id ) return false;
        $param = "{$id}?api_key=$this->api_key&lang=$this->lang&username=$this->email";
        $request_string = "$this->api_url/regions/$param";
        //return $request_string;
        return $this->request($request_string);
    }
    /**********************************************************
     * @param array $args
     * Users
     * ********************************************************
     * POST api.excursiopedia.com/v1/users
     * Регистрирует нового пользователя.
     * ********************************************************
     * GET api.excursiopedia.com/v1/users/:id
     * Возвращает информацию о пользователе, указанном ID
     * * * *** * * * Params:
     * id required
     * An ID of a user.
     * Value: Must be Integer
     */
    public function getUsersID($args = array() ){
        if(!isset($this->status)) return false;
        $defaults = array('id' => false);
        extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );
        if( false === $id ) return false;
        $param = "{$id}";
        $request_string = "$this->api_url/users/$param";
        //return $request_string;
        return $this->request($request_string);
    }
    /**
     * object to array
     * @param $d
     * @return array
     */
    public  function objectToArray($d) {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return array_map(array(&$this, __FUNCTION__), $d);
        }
        else {
            // Return array
            return $d;
        }
    }
    /**
     * Функция которой передаётся url и которая возвращает body ответа
     **/
    public function request( $string ){
        $string = htmlspecialchars($string);
        /**
         * wp_remote_get( $url, $args );
         * Получает удаленную страницу используя HTTP GET метод.
         * Обертка для использования curl. Результат содержит HTTP
         * заголовки и данные самой станицы, и возвращается в виде массива.
         */
        $response = wp_remote_get($string, array('headers' => array(
            'Accept-Encoding' => 'gzip, deflate',
        )));
        if( is_wp_error( $response ) ){
            $json = $response;
        } else {
            $json = json_decode( $response['body'] );
        }
        return $this->objectToArray($json);
    }

}