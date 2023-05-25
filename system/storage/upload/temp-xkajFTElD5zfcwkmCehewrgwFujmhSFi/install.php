<?php

define('NEAT_COUNTDOWN_TARGET', '3.0.x');

// Opencart 3.0 won't use this file

function neat_countdown_install() {
    $oc_version = substr(VERSION, 0, 3);

    if ($oc_version < '2.1') {
        throw new Exception('Unsupported Opencart version: ' . VERSION);
    }

    if (false === strpos(NEAT_COUNTDOWN_TARGET, $oc_version)) {
        throw new Exception('This Neat Countdown build is incompatible with current Opencart version. Get build for version ' . $oc_version);
    }

    // clean residual stuff

    // We installed `require.js` but it's not absolutely safe
    // to remove it: maybe someone lately got it to use. It's popular lib.
    // @unlink('../catalog/view/javascript/require.js');
    @unlink('../catalog/view/theme/default/template/extension/module/neat_countdown/simple_view.tpl');
    @unlink('../catalog/view/theme/default/template/extension/module/neat_countdown/textual_view.tpl');
    @rmdir('../catalog/view/theme/default/template/extension/module/neat_countdown');

    $code_list = array( 'en-gb' => 'english', 'ru-ru' => 'russian', 'ua-uk' => 'ukrainian' );

    if ($oc_version == '2.1') {
        foreach ($code_list as $code => $lang) {
            @mkdir("language/$lang/module", 0775, true);
            @mkdir("../catalog/language/$lang/module", 0775, true);

            if (rename("language/$code/module/neat_countdown.php", "language/$lang/module/neat_countdown.php")) {
                @rmdir("language/$code/module");
                @rmdir("language/$code");
            }
            if (rename("../catalog/language/$code/module/neat_countdown.php", "../catalog/language/$lang/module/neat_countdown.php")) {
                @rmdir("../catalog/language/$code/module");
                @rmdir("../catalog/language/$code");
            }
        }
    } else {
        foreach ($code_list as $code => $lang) {
            // usually will affect only version 2.2
            @unlink("language/$lang/module/neat_countdown.php");
            @rmdir("language/$lang/module");
            @rmdir("language/$lang");
            @unlink("../catalog/language/$lang/module/neat_countdown.php");
            @rmdir("../catalog/language/$lang/module");
            @rmdir("../catalog/language/$lang");
        }
    }
}

neat_countdown_install();
