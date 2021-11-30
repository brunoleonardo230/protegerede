<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_DOCTORS;

if (!APP_DOCTORS || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['alert'] = ["red", "wondering2", "OPSSS", "Você Não Tem Permissão Para Essa Ação ou Não Está Logado Como Administrador!"];
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Doctors';
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

    // AUTO INSTANCE OBJECT CREATE
    if (empty($Create)):
        $Create = new Create;
    endif;

    // AUTO INSTANCE OBJECT UPDATE
    if (empty($Update)):
        $Update = new Update;
    endif;

    // AUTO INSTANCE OBJECT DELETE
    if (empty($Delete)):
        $Delete = new Delete;
    endif;

    // AUTO INSTANCE OBJECT EMAIL
    if (empty($Email)):
        $Email = new Email;
    endif;

    //SELECIONA AÇÃO
    switch ($Case):
        //GERENCIA MÉDICO
        case 'manager':
            $DoctorId = $PostData['doctor_id'];
            unset($PostData['doctor_id']);

            $Read->ExeRead(DB_DOCTORS, "WHERE doctor_id = :id", "id={$DoctorId}");
            $ThisDoctor = $Read->getResult()[0];

            if (!empty($_FILES['doctor_cover'])):
                $File = $_FILES['doctor_cover'];

                if ($ThisDoctor['doctor_cover'] && file_exists("../../uploads/{$ThisDoctor['doctor_cover']}") && !is_dir("../../uploads/{$ThisDoctor['doctor_cover']}")):
                    unlink("../../uploads/{$ThisDoctor['doctor_cover']}");
                endif;

                $Upload = new Upload('../../uploads/');
                $Upload->Image($File, Check::Name($PostData['doctor_name']) . '-' . time(), IMAGE_W, 'medicos');
                if ($Upload->getResult()):
                    $PostData['doctor_cover'] = $Upload->getResult();
                else:
                    $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR FOTO", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG Ou PNG Para Enviar Como Foto!"];
                    echo json_encode($jSON);
                    return;
                endif;
            else:
                unset($PostData['doctor_cover']);
            endif;

            $PostData['doctor_url'] = (!empty($PostData['doctor_url']) ? Check::Name($PostData['doctor_url']) : Check::Name($PostData['doctor_name']));
            $PostData['doctor_status'] = (!empty($PostData['doctor_status']) ? '1' : '0');
            $PostData['doctor_datebirth'] = (!empty($PostData['doctor_datebirth']) ? Check::Nascimento($PostData['doctor_datebirth']) : '');
            $PostData['doctor_datecreate'] = (!empty($PostData['doctor_datecreate']) ? Check::Data($PostData['doctor_datecreate']) : date('Y-m-d H:i:s'));

            $Update->ExeUpdate(DB_DOCTORS, $PostData, "WHERE doctor_id = :id", "id={$DoctorId}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Médico <b>{$PostData['doctor_name']}</b> Foi Atualizado Com Sucesso!"];
            break;

        //DELETE MÉDICO
        case 'delete':
            $PostData['doctor_id'] = $PostData['del_id'];
            $Read->FullRead("SELECT doctor_cover FROM " . DB_DOCTORS . " WHERE doctor_id = :ps", "ps={$PostData['doctor_id']}");
            if ($Read->getResult() && file_exists("../../uploads/{$Read->getResult()[0]['doctor_cover']}") && !is_dir("../../uploads/{$Read->getResult()[0]['doctor_cover']}")):
                unlink("../../uploads/{$Read->getResult()[0]['doctor_cover']}");
            endif;

            $Delete->ExeDelete(DB_DOCTORS, "WHERE doctor_id = :id", "id={$PostData['doctor_id']}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Dentista Foi Excluído Com Sucesso!"];
            break;

        //PAGINAÇÃO VIA AJAX MÉDICOS HOME  
        case 'content':
            $jSON['content'] = null;

            if (isset($PostData['search'])):
                $search = $PostData['search'];
                $Read->ExeRead(DB_DOCTORS, "WHERE (doctor_name LIKE '%' :search '%' OR doctor_email LIKE '%' :search '%' OR doctor_number_advice LIKE '%' :search '%') LIMIT :limit", "search={$search}&limit=10");
            endif;

            if (isset($PostData['offset'])):
                $offset = $PostData['offset'];
                $Read->ExeRead(DB_DOCTORS, "LIMIT :limit OFFSET :offset", "limit=10&offset={$offset}");
            endif;

            if ($Read->getResult()):
                foreach ($Read->getResult() as $DOCTORS):
                    extract($DOCTORS);

                    if (empty($doctor_cover) && $doctor_genre == 1):
                        $DoctorImage = "../tim.php?src=admin/_img/avatarm.png&w=40&h=40";
                    elseif (empty($doctor_cover) && $doctor_genre == 2):
                        $DoctorImage = "../tim.php?src=admin/_img/avatarf.png&w=40&h=40";
                    else:
                        $DoctorImage = BASE . "/tim.php?src=uploads/{$doctor_cover}&w=40&h=40";
                    endif;

                    $DoctorLink = "dashboard.php?wc=medicos/create&id={$doctor_id}";

                    $jSON['content'] .= "<article class='marketing__table js-marketing-table js-rel-to' id='{$doctor_id}'> <div class='marketing__data'> <p class='payment'> <span class='img'> <img src='{$DoctorImage}'/> </span> </p> <p>" . Check::Chars($doctor_name, 25) . "</p> <p class='icon-envelop'>" . Check::Chars($doctor_email, 25) . "</p> <p class=' icon-file-text'>{$doctor_number_advice}</p> <p class='icon-phone'>{$doctor_cell}</p> <p> <a title='Editar Dentista' class='btn_header btn_darkaquablue icon-pencil icon-notext' href='{$DoctorLink}'></a> <span title='Excluir Dentista' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Dentists' callback_action='delete' id='{$doctor_id}'></span> </p> </div> </article>";
                endforeach;
            endif;
            break;

        //BUSCA DINÂMICA MÉDICOS MODAL  
        case 'search':
            $search = $PostData['search'];
            $jSON['content'] = null;

            $Read->ExeRead(DB_DOCTORS, "WHERE doctor_name LIKE '%' :search '%'", "search={$search}");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $DOCTORS):
                    extract($DOCTORS);

                    $jSON['content'] .= "<li class='modal__item'>";
                    $jSON['content'] .= "<span class='modal__link js-user-toggle' data-name='{$doctor_name}' data-id='{$doctor_id}'>";
                    $jSON['content'] .= "{$doctor_name} <i class='icon-checkmark icon-notext modal__check'></i>";
                    $jSON['content'] .= "</span>";
                    $jSON['content'] .= "</li>";

                endforeach;
            endif;
            break;

        //ENVIA E-MAIL PARA OS MÉDICOS
        case 'send':
            require '../_tpl/Client.email.php';
            extract($PostData);

            if (empty($doctors)):
                $jSON['alert'] = ["yellow", "warning", "OPSSS {$_SESSION['userLogin']['user_name']}", "Selecione os Médicos!"];
                break;
            endif;

            if (empty($subject)):
                $jSON['alert'] = ["yellow", "warning", "OPSSS {$_SESSION['userLogin']['user_name']}", "Defina o Assunto!"];
                break;
            endif;

            if (empty($body)):
                $jSON['alert'] = ["yellow", "warning", "OPSSS {$_SESSION['userLogin']['user_name']}", "Defina a Mensagem!"];
                break;
            endif;

            $arrSearch = [
                '{doctor_name}',
                '{doctor_email}',
                '{doctor_number_advice}',
                '{doctor_cell}'
            ];

            if ($doctors == 'all'):
                $Read->ExeRead(DB_DOCTORS);
                foreach ($Read->getResult() as $Doctor):
                    extract($Doctor);

                    $arrReplace = [
                        $doctor_name,
                        $doctor_email,
                        $doctor_number_advice,
                        $doctor_cell
                    ];

                    $replaces = str_replace($arrSearch, $arrReplace, $body);
                    $mensagem = str_replace('#mail_body#', $replaces, $MailContent);
                    $Email->EnviarMontando($subject, $mensagem, ADMIN_NAME, MAIL_USER, $doctor_name, $doctor_email);
                endforeach;
            else:
                foreach (explode(',', $Doctors) as $Doctor):
                    $Read->ExeRead(DB_DOCTORS, "WHERE doctor_id = :id", "id={$Doctor}");
                    extract($Read->getResult()[0]);

                    $arrReplace = [
                        $doctor_name,
                        $doctor_email,
                        $doctor_number_advice,
                        $doctor_cell
                    ];

                    $replaces = str_replace($arrSearch, $arrReplace, $body);
                    $mensagem = str_replace('#mail_body#', $replaces, $MailContent);
                    $Email->EnviarMontando($subject, $mensagem, ADMIN_NAME, MAIL_USER, $doctor_name, $doctor_email);
                endforeach;
            endif;

            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O E-mail Foi Enviado Com Sucesso!"];
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
