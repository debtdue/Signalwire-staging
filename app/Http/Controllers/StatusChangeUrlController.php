<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SignalWire\LaML;

class StatusChangeUrlController extends Controller
{
    /**
     * SignalWire LaMl object
     *
     * @var \SignalWire\LaML
     */
    public $laml;

    /**
     * RecordingStatusCallbackController constructor.
     */
    public function __construct()
    {
        $this->laml = new LaML();
    }

    public function handle(Request $request)
    {
        return $this->laml;
    }
}
