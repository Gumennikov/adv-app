<?php


namespace App\Http\Controllers\Cabinet\Adverts;


use App\Entity\Adverts\Advert\Advert;
use App\UseCases\Adverts\FavoriteService;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends \App\Http\Controllers\Controller
{
    private $service;

    public function __construct(FavoriteService $service)
    {
        $this->service = $service;
        $this->middleware('auth');
    }

    public function index(Advert $advert)
    {
        $adverts = Advert::favoredByUser(Auth::user())->orderByDesc('id')->paginate(20);

        return view('cabinet.favorites.index', compact('adverts'));
    }

    public function remove(Advert $advert)
    {
        try {
            $this->service->remove(Auth::id(), $advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.favorites.index');
    }
}
