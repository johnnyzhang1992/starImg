<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;
use App\Models\Star;
use Illuminate\Support\Facades\DB;

class SiteMapController extends Controller{
    //
    public function index(){

        // create new sitemap object
        $sitemap = App::make('sitemap');

        // set cache key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean)
        // by default cache is disabled
        $sitemap->setCache('laravel.sitemap', 60);

        // check if there is cached sitemap and build new only if is not
        if (!$sitemap->isCached()) {
            // add item to the sitemap (url, date, priority, freq)
            $sitemap->add(URL::to('/'), '2012-08-25T20:10:00+02:00', '1.0', 'daily');

            // add item with translations (url, date, priority, freq, images, title, translations)
//            $translations = [
//                ['language' => 'fr', 'url' => URL::to('pageFr')],
//                ['language' => 'de', 'url' => URL::to('pageDe')],
//                ['language' => 'bg', 'url' => URL::to('pageBg')],
//            ];
//            $sitemap->add(URL::to('pageEn'), '2015-06-24T14:30:00+02:00', '0.9', 'daily', [], null, $translations);

            // get all stars from db
            $stars = DB::table('star')
                ->leftJoin('star_wb','star_wb.star_id','=','star.id')
//            ->leftJoin('star_ins','star_wb.star_id','=','star.id')
                ->where('star.status','=','active')
                ->select('star.*','star_wb.verified')
                ->orderBy('id','asc')
                ->paginate(30);

            // add every star to the sitemap
            foreach ($stars as $star){
                $sitemap->add(URL::to($star->domain),$star->created_at,'0.9','daily');
            }
        }

//        $sitemap->store('xml', 'stars');
        // show your sitemap (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
        return $sitemap->render('xml');
    }
}
