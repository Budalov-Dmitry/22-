<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateShotLinkRequest;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use function PHPUnit\Framework\isEmpty;

class LinkController extends Controller
{
    public function createShortLink(CreateShotLinkRequest $request)
    {
        $data =  $request->only('url');

        $shortLink = Str::random(5);

        try
        {
            Link::upsert(
                [
                    'full_link' => $data['url'],
                    'short_link' => $shortLink,
                ],
                ['full_link'],
                ['short_link']);
        }
        catch (Exception $e)
        {
           return response()->json([
               'status' => 'fail',
           ]);
        }

        return response()->json([
            'status' => 'ok',
            'short_link' => $shortLink,
        ]);
    }

    public function shortLinkRedirect($shortLink)
    {
            $url = Link::where('short_link',$shortLink)->first();

            if ($url)
            {
                return redirect()->away($url->full_link);
            }

        return response()->json([
            'status' => 'fail to redirect',
        ]);
    }
}
