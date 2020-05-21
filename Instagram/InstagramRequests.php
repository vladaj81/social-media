<?php
//CLASS FOR REQUESTING DATA FROM INSTAGRAM 
class InstagramRequests 
{
    private $fbObject;
    private $appId;
    private $appSecret;
    private $appToken;
    private $instagramId;
    private $response;
    private $postArray;
    private $error;
    
    //CONSTRUCT FUNCTION TAKES 4 ARGUMENTS: FACEBOOK API CREDENTIALS AND ID OF CONNECTED INSTAGRAM ACCOUNT
    public function __construct($appId, $appSecret, $appToken, $instagramId) 
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->appToken = $appToken;
        $this->instagramId = $instagramId;

        //CREATING FACEBOOK OBJECT AND SET PROPERTIES APP-ID AN APP-SECRET FROM REGISTERED FACEBOOK APP ON DEVELOPERS.FACEBOOK.COM
        $this->fbObject = new \Facebook\Facebook([
            'app_id' => $this->appId,           //Replace {your-app-id} with your app ID
            'app_secret' => $this->appSecret,   //Replace {your-app-secret} with your app secret
            'graph_api_version' => 'v6.0',
        ]);
    }
    

    //FUNCTION FOR REQUESTING INSTAGRAM POST DATA.(LATER CAN BE ADDED QUERY PARAMETER)
    public function getInstagramPosts() 
    {
        try {
            //SENDING GET REQUEST TO FACEBOOK GRAPH API FOR INSTAGRAM POSTS 
            $this->response = $this->fbObject->get($this->instagramId.'/media?fields=comments{username,timestamp,text,replies{username,text},user,like_count},caption,permalink,username,thumbnail_url,media_url,media_type,like_count,timestamp', 
            $this->appToken);

            //ASIGNING RETREIVED DATA FROM API RESPONSE TO PROPERTY
            $this->postArray = $this->response->getGraphEdge()->asArray();

        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // RETURNS GRAPH API ERRORS WHEN THEY OCCUR
            $this->error = '<div class="error">You entered wrong credentials. Click this button to update them.</div>&nbsp;&nbsp;';
            $this->error .= '<form method="post">
                                <button type="submit" class="btn btn-danger" name="updateInstaCredentials" value=update">
                                    Update credentials
                                </button>
                            </form>';

        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // RETURNS SDK ERRORS WHEN VALIDATION FAILS OR OTHER LOCAL ISSUES
            $this->error .= '<p class="error">Facebook SDK returned an error: ' . $e->getMessage() . '</p>';
        }

        //RETURN THE ERROR IF THERE IS,OTHERWISE RETURN POSTS HTML
        if($this->error) { return $this->error; }

        return $this->generatePostsHtml($this->postArray);
    }


    //FUNCTION WHICH GENERATES HTML FOR RECEVIED INSTAGRAM POSTS.PARAMETER IS AN ARRAY OF POSTS.
    public function generatePostsHtml(array $postArray) 
    {
        $htmlOutput = '';

        //ITERATING THROUGH ARRAY AND CREATING HTML OUTPUT
        foreach ($postArray as $item) {

            //CHECKING IF POST IS AN IMAGE
            if($item['media_type'] == 'IMAGE') {

                //CONVERTING POST DATE INTO APPROPRIATE FORMAT
                $date = date_create($item['timestamp']);
                $postDate = date_format($date, 'd-m-Y');

                //OPENING POST CONTAINER DIV AND POST WRAPPER DIV
                $htmlOutput .= '<div class="col-lg-4 col-md-6">
                                    <div class="postWrapper">';

                //ADDING POST AUTHOR AND POST DATE TO OUTPUT VARIABLE
                $htmlOutput .= '<div class="postDetails postAuthor">
                                    <span class="author">' .$item['username']. '&nbsp;&nbsp;</span> 
                                    <span class="postDate">' .$postDate. '</span>
                                </div>';
                
                //ADDING IMAGE TO OUTPUT VARIABLE          
                $htmlOutput .= '<div class="imageWrapper">
                                    <img src=' .$item["media_url"]. ' width="100%" height="240">
                                </div>';

                //CHECKING IF THERE ARE POST LIKES TO DISPLAY
                if(isset($item['like_count'])) {

                    $htmlOutput .= '<div class="postDetails">
                                        <span class="likes">' .$item['like_count']. ' likes</span>
                                    </div>';
                }

                //CHECKING IF THERE IS A CAPTION TO DISPLAY
                if(isset($item['caption'])) {

                    $htmlOutput .= '<div class="postDetails">
                                        <span class="user">' .$item['username']. '&nbsp;&nbsp;</span>
                                        <span class="hashtag">' .$item["caption"]. '</span>
                                    </div>';
                }

                //CHECKING IF THERE ARE POST COMMENTS TO DISPLAY
                if(isset($item['comments'])) {

                    //DISPLAYING CREATOR AND TEXT OF COMMENTS
                    foreach ($item['comments'] as $comment) {

                        $htmlOutput .= '<div class="postDetails">
                                            <span class="user">' .$comment['username']. '&nbsp;&nbsp;&nbsp;</span> 
                                            <span class="commentText">' .$comment['text']. '</span>
                                        </div>';
                    }
                }

                //CHECKING IF THERE IS A PERMALINK TO POST
                if(isset($item['permalink'])) {

                    $htmlOutput .= '<div class="postDetails postLink">
                                        <a href="' .$item['permalink']. '" target="_blank">Visit post</a>
                                    </div>';
                }

                $htmlOutput .=      '</div>
                                </div>';
            } 
        }

        return $htmlOutput;
    }
}
?>