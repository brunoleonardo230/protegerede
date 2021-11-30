<?php

ob_start();
session_start();

if (empty($_SESSION['wc_hello'])):
    $_SESSION['wc_hello'] = array();
endif;

require '../../../_app/Config.inc.php';
$getPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (!empty($getPost)):

    if (!empty($getPost['action']) && $getPost['action'] == 'track'):
        $HelloId = $getPost['hello'];
        $Read = new Read;
        $Read->FullRead("SELECT hello_clicks FROM " . DB_HELLO . " WHERE hello_id = :id", "id={$HelloId}");
        if ($Read->getResult()):
            $UpdateHello = ["hello_clicks" => $Read->getResult()[0]['hello_clicks'] + 1];
            $Update = new Update;
            $Update->ExeUpdate(DB_HELLO, $UpdateHello, "WHERE hello_id = :id", "id={$HelloId}");

            setcookie("wc_hello", $HelloId, time() + 604800, "/");
        endif;
    elseif (!empty($getPost['url'])):

        $HelloUrl = str_replace(BASE, "", $getPost['url']);
        $HelloArr = array_filter(explode("/", $HelloUrl));
        $HelloClear = array_map("strip_tags", $HelloArr);
        $HelloKeys = "'" . implode("','", $HelloClear) . "'";

        $WhereSession = null;
        if (!empty($_SESSION['wc_hello'])):
            $WhereSession = "hello_id NOT IN('" . implode("','", $_SESSION['wc_hello']) . "') AND";
        endif;

        $getCookie = filter_input(INPUT_COOKIE, "wc_hello", FILTER_VALIDATE_INT);
        $WhereCookie = null;
        if ($getCookie):
            $WhereCookie = "hello_id != {$getCookie} AND";
        endif;

        $Read->FullRead(
                "SELECT "
                . "h.* "
                . "FROM " . DB_HELLO . " h "
                . "WHERE {$WhereSession} {$WhereCookie} (hello_rule IN({$HelloKeys}) OR hello_rule IS NULL) "
                . "AND hello_start <= NOW() AND hello_end >= NOW() AND hello_status = 1 "
                . "LIMIT 1"
        );

        if ($Read->getResult()):
            extract($Read->getResult()[0]);

            $UpdateHello = ["hello_views" => $hello_views + 1];
            $Update = new Update;
            $Update->ExeUpdate(DB_HELLO, $UpdateHello, "WHERE hello_id = :id", "id={$hello_id}");

            $jSON['hello'] = "<div class='wc_hellobar wc_hellobar_{$hello_position}' id='{$hello_id}'>"
                    . "<div class='wc_hellobar_box'>"
                    . "<span id='{$hello_id}' class='wc_hellobar_close icon-cross icon-notext'></span>"
                    . "<img src='" . BASE . "/tim.php?src=uploads/{$hello_image}&w=" . IMAGE_W / 2 . "' alt='{$hello_cta}' title='{$hello_cta}'/>"
                    . "<div class='wc_hellobar_box_content'>"
                    . "<div class='wc_hellobar_box_content_btn'>"
                    . "<a href='{$hello_link}' class='btn btn_cta_{$hello_color} wc_hellobar_cta' title='{$hello_cta} wc_hellobar_cta' id='{$hello_id}'>{$hello_cta}</a>"
                    . "</div>"
                    . "<p>{$hello_title}</p>"
                    . "</div>" //wc_hellobar_box_content
                    . "</div>" //wc_hellobar_box
                    . "</div>";

            $_SESSION['wc_hello'][$hello_id] = $hello_id;
            $jSON['hello_position'] = $hello_position;
            echo json_encode($jSON);
        endif;
    endif;
endif;

ob_end_flush();
