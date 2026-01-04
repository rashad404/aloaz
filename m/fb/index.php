<?php 
include "Facebook/FacebookRequest.php";
/* PHP SDK v5.0.0 */
        /* make the API call */
        $request = new FacebookRequest(
            $session,
            'GET',
            '/{post-id}/reactions'
        );
        $response = $request->execute();
        $graphObject = $response->getGraphObject();
        /* handle the result */

?>