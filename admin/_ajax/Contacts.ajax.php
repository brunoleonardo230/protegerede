<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_CONTACTS;

if (!APP_CONTACTS || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['alert'] = ["red", "wondering2", "OPSSS", "Você Não Tem Permissão Para Essa Ação ou Não Está Logado Como Administrador!"];
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Contacts';
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
        //PAGINAÇÃO VIA AJAX CONTATOS HOME  
        case 'content':
            $jSON['content'] = null;

            if (isset($PostData['search'])):
                $search = $PostData['search'];
                $Read->ExeRead(DB_CONTACTS, "WHERE (contact_name LIKE '%' :search '%' OR contact_email LIKE '%' :search '%') LIMIT :limit", "search={$search}&limit=10");
            endif;

            if (isset($PostData['offset'])):
                $offset = $PostData['offset'];
                $Read->ExeRead(DB_CONTACTS, "LIMIT :limit OFFSET :offset", "limit=10&offset={$offset}");
            endif;

            if ($Read->getResult()):
                foreach ($Read->getResult() as $CONTACTS):
                    extract($CONTACTS);
                    
                    $jSON['content'] .= "<article class='marketing__table js-marketing-table js-rel-to' id='{$contact_id}'> <div class='marketing__data'> <p>" . Check::Chars($contact_name, 30) . "</p> <p class='icon-envelop'>" . Check::Chars($contact_email, 30) . "</p> <p class='icon-phone'>{$contact_telephone}</p> <p> <span title='Excluir Contato' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Contacts' callback_action='delete' id='{$contact_id}'></span> </p> </div> </article>";
                endforeach;
            endif;
            break;    
            
        //BUSCA DINÂMICA CONTATOS MODAL  
        case 'search':
            $search = $PostData['search'];
            $jSON['content'] = null;

            $Read->ExeRead(DB_CONTACTS, "WHERE contact_name LIKE '%' :search '%'", "search={$search}");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $CONTACTS):
                    extract($CONTACTS);

                    $jSON['content'] .= "<li class='modal__item'>";
                        $jSON['content'] .= "<span class='modal__link js-user-toggle' data-name='{$contact_name}' data-id='{$contact_id}'>";
                            $jSON['content'] .= "{$contact_name} <i class='icon-checkmark icon-notext modal__check'></i>";
                        $jSON['content'] .= "</span>";
                    $jSON['content'] .= "</li>";

                endforeach;
            endif;
            break;   
            
        //ENVIA E-MAIL PARA OS CONTATOS
        case 'send':
            require '../_tpl/Mail.email.php';
            extract($PostData);

            if (empty($contacts)):
                $jSON['alert'] = ["yellow", "warning", "OPSSS {$_SESSION['userLogin']['user_name']}", "Selecione os Contatos!"];
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
                '{contact_name}',
                '{contact_email}',
                '{contact_telephone}'
            ];

            if ($contacts == 'all'):
                $Read->ExeRead(DB_CONTACTS);
                foreach ($Read->getResult() as $Contact):
                    extract($Contact);

                    $arrReplace = [
                        $contact_name,
                        $contact_email,
                        $contact_telephone
                    ];

                    $replaces = str_replace($arrSearch, $arrReplace, $body);
                    $mensagem = str_replace('#mail_body#', $replaces, $MailContent);
                    $Email->EnviarMontando($subject, $mensagem, ADMIN_NAME, MAIL_USER, $contact_name, $contact_email);
                endforeach;
            else:
                foreach (explode(',', $contacts) as $Contact):
                    $Read->ExeRead(DB_CONTACTS, "WHERE contact_id = :id", "id={$Contact}");
                    extract($Read->getResult()[0]);

                    $arrReplace = [
                        $contact_name,
                        $contact_email,
                        $contact_telephone
                    ];

                    $replaces = str_replace($arrSearch, $arrReplace, $body);
                    $mensagem = str_replace('#mail_body#', $replaces, $MailContent);
                    $Email->EnviarMontando($subject, $mensagem, ADMIN_NAME, MAIL_USER, $contact_name, $contact_email);
                endforeach;
            endif;

            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O E-mail Foi Enviado Com Sucesso!"];
            break; 
            
        //DELETE CONTATO
        case 'delete':
            $PostData['contact_id'] = $PostData['del_id'];

            $Delete->ExeDelete(DB_CONTACTS, "WHERE contact_id = :id", "id={$PostData['contact_id']}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Contato Foi Excluído Com Sucesso!"];
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