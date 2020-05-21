<?php
//CLASS FOR REQUESTING DATA FROM FACEBOOK 
class FacebookRequests 
{
    private $fbObject;
    private $appId;
    private $appSecret;
    private $appToken;
    private $response;
    private $postArray;
    private $error;
    
    //CONSTRUCT FUNCTION TAKES 3 ARGUMENTS FOR FACEBOOK API AUTHENTICATION
    public function __construct($appId, $appSecret, $appToken) 
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->appToken = $appToken;

        //CREATING FACEBOOK OBJECT AND SET PROPERTIES APP-ID AN APP-SECRET FROM REGISTERED FACEBOOK APP ON DEVELOPERS.FACEBOOK.COM
        $this->fbObject = new \Facebook\Facebook([
            'app_id' => $this->appId,           //Replace {your-app-id} with your app ID
            'app_secret' => $this->appSecret,   //Replace {your-app-secret} with your app secret
            'graph_api_version' => 'v6.0',
        ]);
    }
    

    //FUNCTION FOR REQUESTING FACEBOOK POST DATA.PARAMETER IS FACEBOOK PAGE ID
    public function getFacebookPosts($pageId) 
    {
        try {
            //SENDING GET REQUEST TO FACEBOOK GRAPH API FOR POSTS 
            $this->response = $this->fbObject->get($pageId.'/posts?fields=full_picture,width,height,created_time,message&limit=20',  
            $this->appToken);

            //ASIGNING RETREIVED DATA FROM FACEBOOK RESPONSE TO PROPERTY
            $this->postArray = $this->response->getGraphEdge()->asArray();

        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // RETURNS GRAPH API ERRORS WHEN THEY OCCUR
            $this->error = '<div class="error">You entered wrong credentials. Click this button to update them.</div>&nbsp;&nbsp;';
            $this->error .= '<form method="post">
                                <button type="submit" class="btn btn-danger" name="updateFbCredentials" value=update">
                                    Update credentials
                                </button>
                            </form>';

        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // RETURNS SDK ERRORS WHEN VALIDATION FAILS OR OTHER LOCAL ISSUES
            $this->error .= '<p class="error">Facebook SDK returned an error: ' . $e->getMessage() . '</p>';
        }

        //RETURN THE ERROR IF THERE IS,OTHERWISE RETURN POSTS HTML
        if($this->error) { return $this->error; }

        return $this->getPostsHtml($this->postArray);
    }


    //FUNCTION WHICH GENERATES HTML FOR POSTS RECEVIED FROM FACEBOOK.PARAMETER IS AN ARRAY OF POSTS.
    public function getPostsHtml(array $postArray) 
    {
        $htmlOutput = '';

        //ITERATING THROUGH ARRAY AND CREATING HTML OUTPUT
        foreach ($postArray as $key => $item) {

            //CHECKING IF THERE IS POST MESSAGE OR NOT
            if (isset($item['message'])) {
                
                $htmlOutput .= '<div class="col-md-4">';

                $htmlOutput .= '<img src=' .$item["full_picture"]. ' width="100%" height="220" class="postPicture"><br>';

                $htmlOutput .= '<h3 class="blue">Post content</h3>
                                <p class="messageParagraph">' .$item["message"]. '</p>';
            }

            $htmlOutput .= '</div>';
        }  

        return $htmlOutput;
    }

    //FUNCTION FOR CREATING NEW FACEBOOK POST.PARAMETERS ARE FACEBOOK PAGE ID AND ARRAY WITH POST DETAILS
    public function createFacebookPost($pageId, array $attachment)
    {
        try {
            // POST TO FACEBOOK
            $this->fbObject->post($pageId.'/feed', $attachment, $this->appToken);
            
            //SUCCESS MESSAGE,AFTER POST IS CREATED
            $this->error = '<p class="published">The post was published successfully to the Facebook timeline.</p>';

        } catch(FacebookResponseException $e) {
            // RETURNS GRAPH API ERRORS WHEN THEY OCCUR
            $this->error = '<p class="error">Error while creating Facebook post. Please try again.</p>';

        } catch(FacebookSDKException $e) {
            // RETURNS SDK ERRORS WHEN VALIDATION FAILS OR OTHER LOCAL ISSUES
            $this->error .= '<p class="error">Facebook SDK returned an error: ' . $e->getMessage() . '</p>';
        }
        return $this->error;
    }
}
?>