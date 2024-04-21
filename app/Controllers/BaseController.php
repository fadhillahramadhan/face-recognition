<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Libraries\DataTable;
use App\Libraries\Rest;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    public $dataTable;
    public $rest;

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->dataTable = new DataTable();
        $this->rest = new Rest();

        // E.g.: $this->session = \Config\Services::session();
    }

    function convertDate($date, $lang = 'id', $type = 'text', $formatdate = '.')
    {

        if (!empty($date)) {
            if ($type == 'num') {
                $date_converted = str_replace('-', $formatdate, $date);
            } else {
                $year = substr($date, 0, 4);
                $month = substr($date, 5, 2);
                $month = $this->convertMonth($month, $lang);
                $day = substr($date, 8, 2);

                $date_converted = $day . ' ' . $month . ' ' . $year;
            }
        } else {
            $date_converted = '-';
        }
        return $date_converted;
    }

    function convertDatetime($date, $lang = 'id', $type = 'text', $formatdate = '.', $formattime = ':')
    {

        if (!empty($date)) {
            if ($type == 'num') {
                $date_converted = str_replace('-', $formatdate, str_replace(':', $formattime, $date));
            } else {
                $year = substr($date, 0, 4);
                $month = substr($date, 5, 2);
                $month = $this->convertMonth($month, $lang);
                $day = substr($date, 8, 2);
                $time = strlen($date) > 10 ? substr($date, 11, 8) : '';
                $time = str_replace(':', $formattime, $time);

                $date_converted = $day . ' ' . $month . ' ' . $year . ' ' . $time;
            }
        } else {
            $date_converted = '-';
        }
        return $date_converted;
    }

    function convertMonth($month, $lang = 'id')
    {
        $month = (int) $month;
        switch ($lang) {
            case 'id':
                $arr_month = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
                break;

            default:
                $arr_month = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
                break;
        }

        if (array_key_exists($month - 1, $arr_month)) {
            $month_converted = $arr_month[$month - 1];
        } else {
            $month_converted = '';
        }

        return $month_converted;
    }
}
