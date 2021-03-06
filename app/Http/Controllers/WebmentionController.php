<?php
namespace App\Http\Controllers;

use DB;
use App\Webmention;
use App\Jobs\ProcessWebmention;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Log;

//require_once base_path('vendor/indieauth/client/src/IndieAuth/Client.php');
//
//require_once DIR_BASE . 'libraries/php-mf2/Mf2/Parser.php';
//require_once DIR_BASE . 'libraries/link-rel-parser-php/src/IndieWeb/link_rel_parser.php';
//require_once DIR_BASE . 'libraries/indieauth-client-php/src/IndieAuth/Client.php';

class WebmentionController extends Controller
{
    public function index(Request $request)
    {
        if (!config('splatter.webmention.enabled')) {
            abort(404);
        }
        $source = $request->input('source');
        $target = $request->input('target');
        $vouch = $request->input('vouch');

        // make sure our source and target are valid urls
        if (!$this->isValidUrl($source) || !$this->isValidUrl($target)) {
            abort(400);
        }

        $webmention = Webmention::where(['target_url' => $target, 'source_url' => $source])->get()->first();

        if(!$webmention){
            $webmention = new Webmention;
            $webmention->source_url = $source;
            $webmention->target_url = $target;
        }
        //TODO auto approve change if previously approved?
        

        //if the source is approved, i don't need or want the vouch, i just auto accept it and throw the vouch away
        //  or if I am not using vouches, and i have a valid source, and target, i just auto accept
        if ($this->isApprovedSource($source) || !config('splatter.webmention.use_vouch')) {

            $webmention->status = 'queued';
            $webmention->status_code = 202;
            $webmention->save();
            //TODO include vouch url if set anyway??
            
            $job = new ProcessWebmention($webmention);
            dispatch($job);

            return response('Webmention Accepted', 202);

        } elseif (!$this->isValidUrl($vouch)) {
        // if we are using vouch, and there is not vouch, or its invalid,  respond retry with 449
        //  still save webmention in case i want to approve manually later

            $webmention->status = 'queued';
            $webmention->status_code = 449;
            $webmention->save();

            return response('Retry With vouch')
                ->setStatusCode(449, 'Reply With vouch');

        } elseif ($this->isApprovedSource($vouch)) {
            $webmention->vouch = $vouch;
            $webmention->status = 'queued';
            $webmention->status_code = 202;
            $webmention->save();

            $job = new ProcessWebmention($webmention);
            dispatch($job);
    

            return response('Webmention Accepted', 202);

        } else {

            $webmention->vouch = $vouch;
            $webmention->status = 'queued';
            $webmention->status_code = 449;
            $webmention->save();



            return response('Retry With vouch')
                ->setStatusCode(449, 'Reply With vouch');
        }

    }
    //very basic function to determine if URL is valid, this is certainly a great place for improvement
    //TODO
    private function isValidUrl($url)
    {
        if (!isset($url)) {
            return false;
        }
        if (empty($url)) {
            return false;
        }
        return true;
    }

    private function isApprovedSource($url)
    {
        //TODO
        return true;
    }


}
