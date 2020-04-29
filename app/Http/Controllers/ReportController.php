<?php

namespace App\Http\Controllers;

use App\Agent;
use App\Business;
use App\Call;
use Illuminate\Http\Request;
use PhpParser\Builder;

class ReportController extends Controller
{

    /**
     * Load reports view with data
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $businesses = Business::all();
        $agents     = Agent::all();
        $calls      = Call::select( ['calls.*', 'dnc_numbers.blacklisted as is_blacklisted'] )
                          ->orderBy( 'calls.updated_at', 'desc' )
                          ->leftJoin( 'dnc_numbers', 'calls.from', '=', 'dnc_numbers.number' )
                          ->paginate( 50 );

        $counter = $calls->firstItem();

        return view( 'reports.index', compact( 'businesses', 'agents', 'calls', 'counter' ) );

    }

    /**
     * Show a specific call detail report.
     *
     * @param \App\Call $call
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show( Call $call )
    {
        return view( 'reports.show', compact( 'call' ) );
    }

    /**
     * Process the search query and return results to listing view
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search( Request $request )
    {
        //return $request->all();

        // Now with this search, user can only search with dates, businesses and
        // call durations. So all other methods and options are obsoleted
        $query = Call::query();
        $query = $this->_addBusinessesClause( $query, $request );
        $query = $this->_lookByDates( $query, $request );
        $query = $this->_filterByCallDuration( $query, $request );

         //$calls = $query->get();
        // $calls = $query->toSql();
        // return $calls;

        $calls      = $query->paginate( 50 );
        $counter    = $calls->firstItem();
        $businesses = Business::all();
        $agents     = Agent::all();

        return view( 'reports.index', compact( 'businesses', 'agents', 'calls', 'counter' ) );
    }



    /**
     * Add the businesses check into main query builder object and
     * get results for specific businesses only
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request              $request
     *
     * @return \Illuminate\Database\Eloquent\Builder $query
     */
    private function _addBusinessesClause( $query, Request $request )
    {
        if ( isset( $request->businesses ) and is_array( $request->businesses ) and ! empty( $request->businesses ) ) {
            return $query = $query->whereIn( 'business_id', $request->businesses );
        }

        return $query;
    }

    /**
     * This method gives ability to user so that he can perform any search
     * by its custom search string. This method add various or where
     * clause into query builder object to look by direction,
     * call sid, call status, to number, from number
     * and other from number attributes
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request              $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function _searchByCustomString( $query, Request $request )
    {
        if ( ! empty( $request->custom_search_input ) ) {

            $query = $query->orWhere( 'call_sid', $request->custom_search_input );
            $query = $query->orWhere( 'direction', $request->custom_search_input );
            $query = $query->orWhere( 'dial_call_status', $request->custom_search_input );
            $query = $query->orWhere( 'to', $request->custom_search_input );
            $query = $query->orWhere( 'from', $request->custom_search_input );
            $query = $query->orWhere( 'from_zip', $request->custom_search_input );
            $query = $query->orWhere( 'from_city', $request->custom_search_input );
            $query = $query->orWhere( 'from_state', $request->custom_search_input );
            $query = $query->orWhere( 'from_country', $request->custom_search_input );

            return $query;
        }

        return $query;
    }

    /**
     * This method adds various common hide and show data clauses into
     * query builder object like show completed calls only
     * hide spam calls or like show calls having
     * voice mails only
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request              $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function _applyHideShowChecks( $query, Request $request )
    {

        if ( isset( $request->hide_spam_call ) and $request->hide_spam_call == 'yes' ) {
            $query = $query->where( "dial_call_duration", ">=", '00:00:05' );
        }

        if ( isset( $request->show_voice_mails ) and $request->show_voice_mails == 'yes' ) {
            $query = $query->where( 'recording_type', 'voice_mail' );
        }

        if ( isset( $request->show_completed_calls_only ) and $request->show_completed_calls_only == 'yes' ) {
            $query = $query->where( 'dial_call_status', 'completed' );
        }

        return $query;
    }

    /**
     * This method add between clause for date and time
     * into query builder object
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request              $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function _lookByDates( $query, Request $request )
    {
        if ( ! is_null( $request->by_date ) ) {
            $dateRange = explode( '/', $request->by_date );
            $starts    = $dateRange[ 0 ];
            $end       = $dateRange[ 1 ];
            $query     = $query->whereBetween( 'updated_at', [ $starts, $end ] );

            return $query;
        }

        return $query;
    }

    /**
     * Filter the result set by adding call duration clause into query builder object
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request              $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function _filterByCallDuration( $query, Request $request )
    {
        if ( ! is_null( $request->call_duration ) ) {
            $callDurationRange = explode( ',', $request->call_duration );
            $query             = $query->whereBetween( 'dial_call_duration', [
                $callDurationRange[ 0 ],
                $callDurationRange[ 1 ],
            ] );

            return $query;
        }

        return $query;
    }
    /**
     * Filter the result set by adding call recording duration clause into query builder object
     * A replacer method fo call duration after BandWidth
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request              $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function _filterByRecordingDuration( $query, Request $request )
    {
        //return $query;
        if ( ! is_null( $request->call_duration ) ) {
            // $callDurationRange = explode( ',', $request->call_duration );
            // $query             = $query->whereBetween( 'recording_duration', [
            // 	$callDurationRange[0],
            // 	$callDurationRange[1],
            // ] );
            $query = $query->where( 'recording_duration', '<=', $request->call_duration );

            return $query;
        }

        return $query;
    }

    /* New Report Controller Work */

    /**
     * Export Call Report into CSV file format
     *
     * @param \App\Call $call
     */
    public function exportReport( Call $call )
    {
        // setting csv headers
        $CSVHeaders = [
            'Bandwidth Number',
            'To Number',
            'From Number',
            'Business',
            'Account',
            'Call SID',
            'Call Duration',
            'Direction',
            'Created At',
            'Last Updated',
        ];

        // init headers so that file starts downloading automatically
        exportFileHeaders( "call-report-" . date( "Y-m-d H:i:s" ) . ".csv" );

        $call_report = Call::all();
        // making csv
        echo $this->_makeCSV( $CSVHeaders, $call_report );
        die();
    }

    /**
     * Export Call Report into CSV file format
     *
     * @param \Illuminate\Http\Request $request
     */
    public function exportBusinessReport( Request $request )
    {
        // setting csv headers
        $CSVHeaders = [
            'Bandwidth Number',
            'To Number',
            'From Number',
            'Business',
            'Account',
            'Call SID',
            'Call Duration',
            'Direction',
            'Created At',
            'Last Updated',
        ];

        // init headers so that file starts downloading automatically
        exportFileHeaders( "call-report-" . date( "Y-m-d H:i:s" ) . ".csv" );

        if ( ! is_null( $request->by_date ) ) {
            $dateRange = explode( '/', $request->by_date );
            $starts    = $dateRange[ 0 ];
            $end       = $dateRange[ 1 ];

            //dd( $end);
            $query = Call::whereBetween( 'updated_at', [ $starts, $end ] );
        }
        if ( ! empty( $request->businesses ) ) {
            $query->whereIn( 'business_id', explode( ',', $request->businesses ) );
        }

        $call_report = $query->get();

        // making csv
        echo $this->_makeBusinessCSV( $CSVHeaders, $call_report );
        die();
    }

    /**
     * Create a CSV file format data using the fastest
     * and easy to manage way.
     * @ref https://stackoverflow.com/questions/4249432/export-to-csv-via-php
     *
     * @param array $headers
     * @param array $values
     *
     * @return string
     */
    private function _makeBusinessCSV( $headers = [], $values = [] )
    {

        $csv = implode( ",", $headers ) . PHP_EOL;

        // This is less flexible, but we have more control over the formatting
        foreach ( $values as $row ) :
            $csv .= '"' . $row->voip_number . '",';
            $csv .= '"' . $row->to . '",';
            $csv .= '"' . $row->from . '",';
            $csv .= '"' . $row->business->title . '",';
            $csv .= '"' . $row->account->title . '",';
            $csv .= '"' . $row->call_sid . '",';
            $csv .= '"' . $row->dial_call_duration . '",';
            $csv .= '"' . $row->direction . '",';
            $csv .= '"' . $row->created_at . '",';
            $csv .= '"' . $row->updated_at . '",';
            $csv .= PHP_EOL;
        endforeach;

        return $csv;
    }

    /**
     * Create a CSV file format data using the fastest
     * and easy to manage way.
     * @ref https://stackoverflow.com/questions/4249432/export-to-csv-via-php
     *
     * @param array $headers
     * @param array $values
     *
     * @return string
     */
    private function _makeCSV( $headers = [], $values = [] )
    {

        $csv = implode( ",", $headers ) . PHP_EOL;

        // This is less flexible, but we have more control over the formatting
        foreach ( $values as $row ) :
            $csv .= '"' . $row[ 'voip_number' ] . '",';
            $csv .= '"' . $row[ 'to' ] . '",';
            $csv .= '"' . $row[ 'from' ] . '",';
            $csv .= '"' . $row[ 'business' ][ 'title' ] . '",';
            $csv .= '"' . $row[ 'account' ][ 'title' ] . '",';
            $csv .= '"' . $row[ 'call_sid' ] . '",';
            $csv .= '"' . $row[ 'dial_call_duration' ] . '",';
            $csv .= '"' . $row[ 'direction' ] . '",';
            $csv .= '"' . $row[ 'created_at' ] . '",';
            $csv .= '"' . $row[ 'updated_at' ] . '",';
            $csv .= PHP_EOL;
        endforeach;

        return $csv;
    }
}
