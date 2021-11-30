<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_REPORTS;

if (empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['alert'] = ["red", "wondering2", "OPSSS", "Você Não Tem Permissão Para Essa Ação ou Não Está Logado Como Administrador!"];
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Reports';
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//VALIDA AÇÃO
if ($PostData && $PostData['callback_action'] && $PostData['callback'] == $CallBack):
    //PREPARA OS DADOS
    $Case = $PostData['callback_action'];
    unset($PostData['callback'], $PostData['callback_action']);

    // AUTO INSTANCE OBJECT READ
    if (empty($Read)):
        $Read = new Read;
    endif;

    switch ($Case):
        //EAD :: STUDENTS
        case 'get_report';
            $ReportStart = date("Y-m-d", strtotime(($PostData['start_date'] ? Check::Data($PostData['start_date']) : date("Y-m-01 H:i:s"))));
            $ReportEnd = date("Y-m-d", strtotime(($PostData['end_date'] ? Check::Data($PostData['end_date']) : date("Y-m-d H:i:s"))));

            $_SESSION['wc_report_date'] = [$ReportStart, $ReportEnd];

            $jSON['redirect'] = "dashboard.php?wc={$PostData['report_back']}";
            break;
    endswitch;

    //RETORNA O CALLBACK
    if ($jSON):
        echo json_encode($jSON);
    else:
        $jSON['alert'] = ["red", "wondering2", "Desculpe {$_SESSION['userLogin']['user_name']}", "Uma Ação Do Sistema Não Respondeu Corretamente. Ao Persistir, Contate o Desenvolvedor!"];
        echo json_encode($jSON);
    endif;
else:
    //ACESSO DIRETO
    die('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
endif;
