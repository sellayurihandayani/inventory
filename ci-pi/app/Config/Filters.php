<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

// use CodeIgniter\Filters\InvalidChars;
// use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array
     */
    public $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars' => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        // 'invalidchars'  => InvalidChars::class,
        // 'secureheaders' => SecureHeaders::class,
        'filterPimpinan' => \App\Filters\FilterPimpinan::class,
        'filterGudang' => \App\Filters\FilterGudang::class,
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array
     */

    // 'before' => [
    //     'filterPimpinan' => [
    //         'except' => ['login/*']
    //     ],
    //     'filterGudang' => [
    //         'except' => ['login/*']
    //     ]
    // ],
    // 'after' => [
    //     'filterPimpinan' => ['except' => ['main/*', 'satuan/*', 'kategori/*', 'barang/*', 'barangmasuk/*', 'barangkeluar/*', 'laporan/*', 'pelanggan/*']],
    //     'filterGudang' => ['except' => ['satuan/*', 'kategori/*', 'barang/*', 'barangmasuk/*', 'barangkeluar/*', 'main/*', 'pelanggan/*']],
    //     'toolbar',
    // ],
    public $globals = [
        'before' => [
            'filterPimpinan' => [
                'except' => ['login/*', 'login', '/']
            ],
            'filterGudang' => [
                'except' => ['login/*', 'login', '/']
            ]
        ],
        'after' => [
            'filterPimpinan' => [
                'except' => ['main/*', 'satuan/*', 'kategori/*', 'barang/*', 'barangmasuk/*', 'barangkeluar/*', 'laporan/*', 'pelanggan/*', 'users/*']
            ],
            'filterGudang' => [
                'except' => ['main/*', 'satuan/*', 'kategori/*', 'barang/*', 'barangmasuk/*', 'barangkeluar/*', 'laporan/*', 'pelanggan/*']
            ],
            'toolbar',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['csrf', 'throttle']
     *
     * @var array
     */
    public $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     *
     * @var array
     */
    public $filters = [];
    
}
