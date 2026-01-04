<?php
return [
    'adminEmail' => 'contact@infomob.az',
    'supportEmail' => 'noreply@alochat.com',
    'user.passwordResetTokenExpire' => 3600,

    'recapctha_key' =>  '6LdSSAcTAAAAAB327JM4W97fd2C328gPyM_W0NaZ',
    'recaptcha_secret_key' => '6LdSSAcTAAAAAPOgobO8Q3JBurbSc6JtoC-g7V7m',

    'userOnlineStatusCheckTime' =>1000, //600
    'userOnlineStatusCheckTimeForCount' =>600, //600 only for online user count
    'userLastLoginCheckTime' =>300,

    'activityTimeForCoin' => 3600,

    'activityCoin' => 2,   // if online time > userLastLoginCheckTime increase coin

    'infomobSubCoin' => 10,
    'minCoinsForVipUser' => 20,

    'adminUserId' => 1,

    'changeNicknameCoin' => 10,
    'deleteNicknameCoin' => 20,

    'NotVerifiesUserConversationLimit' => 5,

    // Discovery Filter Default Parameters
    'defaultDiscoveryAgeRange' => '18,40',
    'defaultDiscoveryCountryId' => 0,
    'defaultDiscoveryCityId' => 0,
    'defaultDiscoverySex' => 2,

    // user value parameters
    'aboutIssetValue' => 5,
    'photoIssetValue' => 20,
    'verifyUserValue' => 30,
    'womanValue' => 10,



    'maxMsgCount' => 500,






];
