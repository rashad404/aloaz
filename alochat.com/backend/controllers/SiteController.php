<?php
namespace backend\controllers;

use frontend\models\DateStats;
use common\models\Conversation;
use common\models\ConversationReply;
use common\models\Stats;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {


        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','cron'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout','transactions', 'coin-logs','index','test','online','diagram','country-stats','city-stats','back-stats','ref-stats','generate-pass'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $acilis = time()+microtime();

        $db = Yii::$app->db;
        $data = [];/*
       $manCount =  User::find()
            ->where(['sex' => User::SEX_MAN])
           ->count('id');
$womanCount =  User::find()
            ->where(['sex' => User::SEX_WOMAN])
           ->count('id');
$age_18_25 = User::find()
            ->where('age between 18 and 25')
            ->count('id');
        $age_25_30 = User::find()
            ->where('age between 25 and 30')
            ->count('id');
        $age_30_40 = User::find()
            ->where('age between 30 and 40')
            ->count('id');
 $age_40 = User::find()
            ->where('age > 40')
            ->count('id');
    $age_not_set = User::find()
            ->where('age < 18')
            ->count('id');
$active_messages_count = Conversation::find()
            ->where('deleted_by=0 and by_system=0')
            ->count('id');
     $deactive_messages_count = Conversation::find()
            ->where('deleted_by>0')
            ->count('id');
$messages_count = ConversationReply::find()->where('user_id!=1')->count('id');
        $conversations_count = Conversation::find()->where('by_system=0')->count('id');
$countActive = User::find()
            ->where('last_activity - created_at >= '.$time12)
            ->andWhere('last_activity >='.$time24)
            ->count('id');

        $countActive24 = User::find()
            ->andWhere('last_activity >='.$time24)
            ->count('id');
$countActiveToday = User::find()
            ->andWhere('last_activity >='.$timeToday)
            ->count('id');
   $isset_profile_photo_count = User::find()
            ->where('profile_photo!=""')
            ->count();

        SELECT c.name,count(u.id) as count

FROM `user` as u
 left outer join country as c
on u.country_id=c.id
group by country_id */




        $countries_count = [];

        $users_count = User::getAllUserCount();
        $messages_count =$conversations_count = 0;
        //$messages_count = $db->createCommand('SELECT count(id) FROM conversation_reply WHERE user_id!=1')->queryScalar();
       // $conversations_count = $db->createCommand('SELECT count(id) FROM conversation WHERE by_system=0')->queryScalar();


        $online_users_count = User::getOnlineUserCountForAdmin();
        $online_users_for_device = User::getOnlineUserCount1();



/*        for($i=0;$i<=7;$i++){
            $first = $i;
            $last  = $i-1;
            $date = date('d-m-Y',strtotime($first." days ago"));
            $firstTime = strtotime(date("Y-m-d 00:00",strtotime($first." days ago")));
            $lastTime = strtotime(date("Y-m-d 00:00",strtotime($last." days ago")));

            /*$daycount =   User::find()
                ->andWhere(['between','created_at',$firstTime,$lastTime])
                ->count();

            $daycount = $db->createCommand('SELECT count(id) FROM `user` WHERE created_at BETWEEN :firstTime AND :lastTime')->bindValues([":firstTime" => $firstTime, ":lastTime" => $lastTime])->queryScalar();
            /*$messagecount =   ConversationReply::find()
                ->andWhere(['between','time',$firstTime,$lastTime])
                ->andWhere(['!=','user_id',Yii::$app->params["adminUserId"]])
                ->count();
            $messagecount = 0;
            $data[$date] = [$daycount => $messagecount];

        }*/

        $oldUserActivity = [];
        // Gunluk aktivlik kohne userler ucun
        $oldUserActivity[3] = 0;//$this->dateActivityForOldUser(3);
        $oldUserActivity[7] = 0;//$this->dateActivityForOldUser(7);
        $oldUserActivity[10] = 0;//$this->dateActivityForOldUser(10);
        $oldUserActivity[30] = 0;//$this->dateActivityForOldUser(30);

        $time24 =    strtotime("24 hours ago");
        $time12 =   12*60*60;

        $countActive = $db->createCommand('SELECT count(id) FROM `user` WHERE last_activity - created_at >=:time12 and last_activity >= :time24')->bindValues([":time12" => $time12, ":time24" => $time24])->queryScalar();
        $countActive24 = $db->createCommand('SELECT count(id) FROM `user` WHERE last_activity >= :time24')->bindValues([":time24" => $time24])->queryScalar();




        $timeToday = strtotime(date("Y-m-d 00:00",strtotime("0 days ago")));


        $countActiveToday = $db->createCommand('SELECT count(id) FROM `user` WHERE last_activity >= :timeToday')->bindValues([":timeToday" => $timeToday])->queryScalar();

       // $stats = Stats::find()->orderBy(['date' => SORT_DESC])->limit(10)->asArray()->all();

        $stats = $db->createCommand('SELECT * FROM stats ORDER BY `date` DESC LIMIT 3')->queryAll();

        $begin_date = date("Y-m-d");
        $end_date = date("Y-m-d");
        $limit = '';

        $begin_time_date =  date("Y-m-d 0:0");
        $begin_time = strtotime($begin_time_date);
        if($_POST and $_POST["dateOnline"]){
            $begin_date = htmlspecialchars(trim($_POST["begin_date"]));
            $end_date = htmlspecialchars(trim($_POST["end_date"]));
            $where = 'WHERE `date`>="'.$begin_date.'" and `date`<="'.$end_date.'"';

            if($begin_date=="" and $end_date!==""){
                 $where = 'WHERE `date`<="'.$end_date.'"';
            }elseif($begin_date!=="" and $end_date==""){
                $end_date = date("Y")."-".date('m')."-".(date('d')-1);
                $where = 'WHERE `date`>="'.$begin_date.'" and `date`<="'.$end_date.'"';
            }elseif($begin_date=="" and $end_date==""){
                $begin_date = date("Y")."-".date('m')."-".(date('d')-1);
                $end_date = date("Y-m-d");
                $where = 'WHERE `date`>="'.$begin_date.'" and `date`<"'.$end_date.'"';
            }

            $begin_time_date = $begin_date;
            if($begin_time_date==""){
                $begin_time_date = date("Y-m-d 0:0");
            }

            $begin_time = strtotime($begin_time_date);
        }else{
            $limit = "LIMIT 3";
        }
        $statsForDate = $db->createCommand("SELECT count(`id`) FROM `user` WHERE `last_activity`>=:begin_time")->bindValue(":begin_time",$begin_time)->queryScalar();
        $statsForDateRows = $db->createCommand('SELECT * FROM stats '.$where.' order by `date` desc '.$limit)->queryAll();

        $baglanis = time()+microtime();
        $vaxt  = $baglanis - $acilis;
        return $this->render('index',[
            'users_count' => $users_count,
            'online_users_count' => $online_users_count,
            'conversations_count' => $conversations_count,
            'messages_count' => $messages_count,
            'data' => $data,
            'countries_count' => $countries_count,
            'oldUserActivity' => $oldUserActivity,
            'countActive' => $countActive,
            'countActive24' => $countActive24,
            'countActiveToday' => $countActiveToday,
            'online_users_for_device' => $online_users_for_device,
            'stats' => $stats,
            'begin_date' => $begin_date,
            'begin_time_date' => $begin_time_date,
            'end_date' => $end_date,
            'statsForDate' => $statsForDate,
            'statsForDateRows' => $statsForDateRows,
            'vaxt' => $vaxt
        ]);
    }

    public function actionTransactions()
    {
        $transactions = [];
        $db = Yii::$app->db;
        $where = 'WHERE `payment_status`=1';
        $begin_date =  date("Y-m-d 00:00");
        $end_date =  date("Y-m-d 23:59");
         if($_POST and $_POST["dateOnline"]){
            $begin_date = htmlspecialchars(trim($_POST["begin_date"]));
            $end_date = htmlspecialchars(trim($_POST["end_date"]));
            $where.= ' AND `date`>="'.$begin_date.'" and `date`<="'.$end_date.'"';

            if($begin_date=="" and $end_date!==""){
                $where.= ' AND `date`<="'.$end_date.'"';
            }elseif($begin_date!=="" and $end_date==""){
                $end_date = date("Y")."-".date('m')."-".(date('d')-1);
                $where.= ' AND `date`>="'.$begin_date.'" and `date`<="'.$end_date.'"';
            }elseif($begin_date=="" and $end_date==""){
                $begin_date = date("Y")."-".date('m')."-".(date('d')-1);
                $end_date = date("Y-m-d");
                $where = ' AND `date`>="'.$begin_date.'" and `date`<"'.$end_date.'"';
            }

        }else{
             $where.= ' AND `date`>="'.$begin_date.'" and `date`<="'.$end_date.'"';

         }
         $transaction_sum = $db->createCommand('SELECT sum(`amount`) as `sum` FROM `transactions` '.$where.' ORDER BY `id` DESC')->queryOne();

        $transactions_array = $db->createCommand('SELECT * FROM `transactions` '.$where.' ORDER BY `id` DESC')->queryAll();

        foreach($transactions_array as $transaction){
            $transactions[$transaction["id"]] = $transaction;

            $user_id = $transaction["user_id"];
            $user = $db->createCommand("SELECT nickname FROM `user` WHERE `id`=:id LIMIT 1")->bindValue(":id",$user_id)->queryOne();
            $transactions[$transaction["id"]]["nickname"] = $user["nickname"];

        }

        return $this->render('transactions',[
            'transactions' => $transactions,
            'transaction_sum' => $transaction_sum,
            'begin_date' => $begin_date,
            'end_date' => $end_date
        ]);
    }


    public function actionCoinLogs()
    {
        $logs = [];
        $db = Yii::$app->db;
         $begin_date =  date("Y-m-d 00:00");
        $end_date =  date("Y-m-d 23:59");
        $type = 1;
        $where = 'WHERE `type`="'.$type.'" and ';
        if($_POST and $_POST["dateOnline"]){
            $begin_date = htmlspecialchars(trim($_POST["begin_date"]));
            $end_date = htmlspecialchars(trim($_POST["end_date"]));
            $type = intval($_POST["type"]);
            $where = 'WHERE `type`="'.$type.'" and ';

            $where .= ' `date`>="'.$begin_date.'" and `date`<="'.$end_date.'"';

            if($begin_date=="" and $end_date!==""){
                $where.= '`date`<="'.$end_date.'"';
            }elseif($begin_date!=="" and $end_date==""){
                $end_date = date("Y")."-".date('m')."-".(date('d')-1);
                $where.= ' `date`>="'.$begin_date.'" and `date`<="'.$end_date.'"';
            }elseif($begin_date=="" and $end_date==""){
                $begin_date = date("Y")."-".date('m')."-".(date('d')-1);
                $end_date = date("Y-m-d");
                $where .= '  `date`>="'.$begin_date.'" and `date`<"'.$end_date.'"';
            }

        }else{
            $where.= '  `date`>="'.$begin_date.'" and `date`<="'.$end_date.'"';

        }
        $logs_count = $db->createCommand('SELECT count(`id`) as `c` FROM `coin_logs` '.$where.' ORDER BY `id` DESC')->queryOne();

        $coin_logs = $db->createCommand('SELECT * FROM `coin_logs` '.$where.' ORDER BY `id` DESC')->queryAll();

        $all_logs = $db->createCommand('SELECT `text`,sum(`coins`) as s,count(`id`) as c FROM `coin_logs` '.$where.' GROUP BY `text`')->queryAll();


        foreach($coin_logs as $log){
            $logs[$log["id"]] = $log;

            $user_id = $log["user_id"];
            $user = $db->createCommand("SELECT nickname FROM `user` WHERE `id`=:id LIMIT 1")->bindValue(":id",$user_id)->queryOne();
            $logs[$log["id"]]["nickname"] = $user["nickname"];
            if($log["type"]==1){
                $logs[$log["id"]]["type"] = 'Xərclənib';
            }elseif($log["type"]==2){
                $logs[$log["id"]]["type"] = "Artıtılıb";
            }

            if(intval($log["user_id2"])>0){
                $user2 = $db->createCommand("SELECT nickname FROM `user` WHERE `id`=:id LIMIT 1")->bindValue(":id",$user_id)->queryOne();
                $logs[$log["id"]]["nickname2"] = $user2["nickname"];
            }else{
                $logs[$log["id"]]["nickname2"] = '';
            }

        }


        return $this->render('coin-logs',[
            'logs' => $logs,
            'logs_count' => $logs_count,
            'begin_date' => $begin_date,
            'end_date' => $end_date,
            'type' => $type,
            'all_logs' => $all_logs,
        ]);
    }

    public function actionDiagram()
    {
        $db = Yii::$app->db;
        $manCount = $db->createCommand('SELECT count(id) FROM `user` WHERE `sex`=:sex')->bindValue(':sex',User::SEX_MAN)->queryScalar();
        $womanCount = $db->createCommand('SELECT count(id) FROM `user` WHERE `sex`=:sex')->bindValue(':sex',User::SEX_WOMAN)->queryScalar();


        $age_18_25 = $db->createCommand('SELECT count(id) FROM `user` WHERE age between 18 and 25')->queryScalar();
        $age_25_30 = $db->createCommand('SELECT count(id) FROM `user` WHERE age between 25 and 30')->queryScalar();
        $age_30_40 = $db->createCommand('SELECT count(id) FROM `user` WHERE age between 30 and 40')->queryScalar();
        $age_40 = $db->createCommand('SELECT count(id) FROM `user` WHERE age > 40')->queryScalar();
        $age_not_set = $db->createCommand('SELECT count(id) FROM `user` WHERE age<18')->queryScalar();

        $active_messages_count = $db->createCommand('SELECT count(id) FROM conversation WHERE deleted_by=0 and by_system=0')->queryScalar();
        $deactive_messages_count = $db->createCommand('SELECT count(id) FROM conversation WHERE deleted_by>0')->queryScalar();

        $users_count = User::getAllUserCount();

        $isset_profile_photo_count = $db->createCommand('SELECT count(id) FROM `user` WHERE profile_photo!=""')->queryScalar();
        $empty_profile_photo_count = $users_count - $isset_profile_photo_count;


        return $this->render('diagram',[
            'manCount' => $manCount,
            'womanCount' => $womanCount,
            'age_18_25' => $age_18_25,
            'age_25_30' => $age_25_30,
            'age_30_40' => $age_30_40,
            'age_40' => $age_40,
            'age_not_set' => $age_not_set,
            'active_messages_count' => $active_messages_count,
            'deactive_messages_count' => $deactive_messages_count,
            'isset_profile_photo_count' => $isset_profile_photo_count,
            'empty_profile_photo_count' => $empty_profile_photo_count,
        ]);
    }


    public function actionCountryStats()
    {
        $countries_count = User::findBySql("SELECT c.name,count(u.id) as count
        FROM `user` as u
         left outer join country as c
        on u.country_id=c.id
        group by country_id order by count desc")->asArray()->all();

        return $this->render('country-stats',[
            'countries_count' => $countries_count,

        ]);
    }


    public function actionCityStats()
    {
        $cities_count = User::findBySql("SELECT c.name,count(u.id) as count
        FROM `user` as u
         left outer join city as c
        on u.city_id=c.id
        WHERE c.country_id=17
        group by city_id order by count desc")->asArray()->all();

        return $this->render('city-stats',[
            'cities_count' => $cities_count,

        ]);
    }

    public function actionRefStats()
    {
        $model = new DateStats();
        $where = '';

        if($model->load(Yii::$app->request->post())){
             $datestart = strtotime($model->date_start);
             $dateend = strtotime($model->date_end);
             if($dateend==$datestart || $dateend==""){
                $dateend = $datestart + 24*60*60;
             }
             $where = "WHERE created_at>='".$datestart."' and created_at<='".$dateend."'";
        }



        $refs = User::findBySql("SELECT  count(u.id) as count,u.ref
        FROM `user` as u $where
        group by ref order by count desc")->asArray()->all();

        $regfrom_stats = User::findBySql("SELECT  count(u.id) as count,u.regfrom
        FROM `user` as u $where
        group by regfrom order by count desc")->asArray()->all();

        $onfrom_stats = User::findBySql("SELECT  count(u.id) as count,u.onfrom
        FROM `user` as u $where
        group by onfrom order by count desc")->asArray()->all();



        return $this->render('ref-stats',[
            'refs' => $refs,
            'model' => $model,
            'regfrom_stats' => $regfrom_stats,
            'onfrom_stats' => $onfrom_stats
        ]);
    }

    public function actionBackStats()
    {
        $db = Yii::$app->db;

        $data = [];
        for($i=0;$i<=7;$i++){
            $first = $i;
            $last  = $i-1;
            $date = date('d-m-Y',strtotime($first." days ago"));
            $firstTime = strtotime(date("Y-m-d 00:00",strtotime($first." days ago")));
            $lastTime = strtotime(date("Y-m-d 00:00",strtotime($last." days ago")));

            /*$daycount =   User::find()
                ->andWhere(['between','created_at',$firstTime,$lastTime])
                ->count();*/

            $daycount = $db->createCommand('SELECT count(id) FROM `user` WHERE created_at BETWEEN :firstTime AND :lastTime')->bindValues([":firstTime" => $firstTime, ":lastTime" => $lastTime])->queryScalar();
            $messagecount = $db->createCommand('SELECT count(id) FROM conversation_reply WHERE user_id!=:adminUserId and `time` BETWEEN :firstTime AND :lastTime')->bindValues([':adminUserId' =>Yii::$app->params["adminUserId"] ,':firstTime' => $firstTime, ':lastTime' => $lastTime])->queryScalar();

             $data[$date] = [$daycount => $messagecount];

        }

        $oldUserActivity = [];
        // Gunluk aktivlik kohne userler ucun
        $oldUserActivity[3] =  $this->dateActivityForOldUser(3);
        $oldUserActivity[7] = $this->dateActivityForOldUser(7);
        $oldUserActivity[10] = $this->dateActivityForOldUser(10);
        $oldUserActivity[30] =  $this->dateActivityForOldUser(30);

        return $this->render('back-stats',[
            'oldUserActivity' => $oldUserActivity,
            'data' => $data

        ]);
    }

    public function actionCron()
    {
        $timeToday = strtotime(date("Y-m-d 00:00",strtotime("0 days ago")));

        $countActiveToday = User::find()
            ->andWhere('last_activity >='.$timeToday)
            ->count('id');

        $time24 =    strtotime("24 hours ago");

        $countActive24 = User::find()
            ->andWhere('last_activity >='.$time24)
            ->count('id');

        $time12 =   12*60*60;
        $countActive = User::find()
            ->where('last_activity - created_at >= '.$time12)
            ->andWhere('last_activity >='.$time24)
            ->count('id');


        $oldUserActivity = [];
        // Gunluk aktivlik kohne userler ucun
        $oldUserActivity[3] = $this->dateActivityForOldUser(3);
        $oldUserActivity[7] = $this->dateActivityForOldUser(7);
        $oldUserActivity[10] = $this->dateActivityForOldUser(10);
        $oldUserActivity[30] = $this->dateActivityForOldUser(30);

        $all_day = $countActiveToday;
        $all_24  = $countActive24;
        $back_24  = $countActive;
        $back_3  = $this->dateActivityForOldUser(3);
        $back_7  = $this->dateActivityForOldUser(7);
        $back_10  = $this->dateActivityForOldUser(10);
        $back_30 = $this->dateActivityForOldUser(30);

        $stats = Stats::find()->where(['date' => date('Y-m-d')])->one();

        if($stats) {
            $stats->all_day = $all_day;
            $stats->all_24 = $all_24;
            $stats->back_24 = $back_24;
            $stats->back_3 = $back_3;
            $stats->back_7 = $back_7;
            $stats->back_10 = $back_10;
            $stats->back_30 = $back_30;
            $stats->save(false);
        } else {
            $stats = new Stats();
            $stats->all_day = $all_day;
            $stats->all_24 = $all_24;
            $stats->back_24 = $back_24;
            $stats->back_3 = $back_3;
            $stats->back_7 = $back_7;
            $stats->back_10 = $back_10;
            $stats->back_30 = $back_30;
            $stats->date = date("Y-m-d");
            $stats->save(false);
        }

    }


    public function dateActivityForOldUser($day)
    {

        $time = strtotime(date('d-m-Y 00:00',strtotime($day." days ago")));
        $date = strtotime("1 days ago");
        $countActive = User::find()
            ->where('created_at <= '.$time)
            ->andWhere('last_activity >='.$date)
            ->count('id');

        return $countActive;
    }


    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) &&  User::isUserAdmin($model->email)  && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionTest()
    {
        $ip =  Yii::$app->ipgeobase->getIP();

        $geoData = Yii::$app->ipgeobase->getLocation($ip);

        $countryId = intval($geoData['country_id']);

        $cityId = intval($geoData['city_id']);

    }


}
