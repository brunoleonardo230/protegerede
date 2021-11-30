<?php

session_start();
require '../../_app/Config.inc.php';

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Login';
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//VALIDA AÇÃO
if ($PostData && $PostData['callback_action'] && $PostData['callback'] == $CallBack):
    //PREPARA OS DADOS
    $Case = $PostData['callback_action'];
    unset($PostData['callback'], $PostData['callback_action']);

    //ELIMINA CÓDIGOS
    $PostData = array_map('strip_tags', $PostData);

    //SELECIONA AÇÃO
    switch ($Case):
        //LOGIN
        case 'admin_login':
            if (in_array('', $PostData)):
                $jSON['alert'] = ["blue", "warning", "<b>OPSSS</b>", "Informe Seu E-mail e Senha Para Logar!"];
            else:
                if (!Check::Email($PostData['user_email']) || !filter_var($PostData['user_email'], FILTER_VALIDATE_EMAIL)):
                    $jSON['alert'] = ["blue", "warning", "<b>OPSSS</b>", "E-mail Informado Não é Válido!"];
                elseif (strlen($PostData['user_password']) < 5):
                    $jSON['alert'] = ["yellow", "warning", "<b>OPSSS</b>", "Senha Informada Não é Compatível!"];
                else:
                    $Read = new Read;
                    $Read->FullRead("SELECT user_id FROM " . DB_USERS . " WHERE user_level >= :lv", "lv=6");
                    if (!$Read->getResult()):
                        $AdminWorkControl = ['user_id' => 1, 'user_thumb' => 'images/2016/02/1-adminwork-control.png', 'user_name' => 'Admin', 'user_lastname' => 'Work Control', 'user_email' => 'admin@workcontrol.com.br', 'user_password' => hash('sha512', 'admin'), 'user_registration' => date('Y-m-d H:i:s'), 'user_level' => 10];
                        $Create = new Create;
                        $Create->ExeCreate(DB_USERS, $AdminWorkControl);
                    endif;

                    $Read->FullRead("SELECT user_id FROM " . DB_USERS . " WHERE user_email = :email", "email={$PostData['user_email']}");
                    if (!$Read->getResult()):
                        $jSON['alert'] = ["yellow", "warning", "<b>OPSSS</b>", "E-mail Informado Não é Cadastrado!"];
                    else:
                        //CRIPTIGRAFA A SENHA
                        $PostData['user_password'] = hash('sha512', $PostData['user_password']);

                        $Read->FullRead("SELECT user_id FROM " . DB_USERS . " WHERE user_email = :email AND user_password = :pass", "email={$PostData['user_email']}&pass={$PostData['user_password']}");
                        if (!$Read->getResult()):
                            $jSON['alert'] = ["red", "wondering2", "<b>ERRO</b>", "E-mail e Senha Não Conferem!"];
                        else:
                            $Read->ExeRead(DB_USERS, "WHERE user_email = :email AND user_password = :pass AND user_level >= :level", "email={$PostData['user_email']}&pass={$PostData['user_password']}&level=6");
                            if (!$Read->getResult()):
                                $jSON['alert'] = ["red", "wondering2", "<b>ERRO</b>", "Você Não Tem Permissão Para Acessar o Painel!"];
                            else:
                                $Remember = (isset($PostData['user_remember']) ? 1 : null);
                                if ($Remember):
                                    setcookie('workcontrol', $PostData['user_email'], time() + 2592000, '/');
                                else:
                                    setcookie('workcontrol', '', 60, '/');
                                endif;

                                if (!EAD_STUDENT_MULTIPLE_LOGIN):
                                    $wc_ead_login_cookie = hash("sha512", time());
                                    setcookie('wc_ead_login', $wc_ead_login_cookie, time() + 2592000, '/');

                                    $UpdateUserLogin = ['user_lastaccess' => date('Y-m-d H:i:s'), 'user_login' => time(), 'user_login_cookie' => $wc_ead_login_cookie];
                                    $Update = new Update;
                                    $Update->ExeUpdate(DB_USERS, $UpdateUserLogin, "WHERE user_id = :user", "user={$Read->getResult()[0]['user_id']}");
                                endif;

                                $_SESSION['userLogin'] = $Read->getResult()[0];
                                
                                $jSON['alert'] = ["green", "checkmark", "<b>Olá {$Read->getResult()[0]['user_name']},</b>", "Seja Bem-Vindo(a) de Volta!"];
                                $jSON['redirect'] = 'dashboard.php?wc=home';
                            endif;
                        endif;
                    endif;
                endif;
            endif;
            break;

        case 'admin_recover':
            if (isset($PostData['user_email']) && Check::Email($PostData['user_email']) && filter_var($PostData['user_email'], FILTER_VALIDATE_EMAIL)):
                $Read = new Read;
                $Read->FullRead("SELECT user_id, user_name, user_email, user_password FROM " . DB_USERS . " WHERE user_email = :email AND user_level >= :level", "email={$PostData['user_email']}&level=6");
                if (!$Read->getResult()):
                    $jSON['alert'] = ["yellow", "warning", "<b>OPSSS</b>", "E-mail Não Cadastrado ou Não Tem Permissão Para o Painel!"];
                else:
                    $CodeReset = "user_id={$Read->getResult()[0]['user_id']}&user_email={$Read->getResult()[0]['user_email']}&user_password={$Read->getResult()[0]['user_password']}";
                    $CodePass = base64_encode($CodeReset);

                    require '../_tpl/Mail.email.php';
                    $BodyMail = "
                    <p style='font-size: 1.5em;'>Olá {$Read->getResult()[0]['user_name']}, recupere sua senha do " . ADMIN_NAME . "!</p>
                    <p>Caso não tenha feito essa solicitação. Por favor ignore esse e-mail e nenhuma ação será tomada quanto aos dados de acesso!</p>
                    <p>Ou para criar uma nova senha de acesso <a title='Criar Nova Senha' href='" . BASE . "/admin/newpass.php?key={$CodePass}'>CLIQUE AQUI!</a>!</p>
                    <p>Você será redirecionado para uma página onde poderá definir uma nova senha de acesso ao painel! Cuide bem dos seus dados.</p>
                    ";
                    $Mensagem = str_replace('#mail_body#', $BodyMail, $MailContent);

                    $Email = new Email;
                    $Email->EnviarMontando('Recupere sua Senha', $Mensagem, ADMIN_NAME, MAIL_USER, $Read->getResult()[0]['user_name'], $Read->getResult()[0]['user_email']);

                    $_SESSION['trigger_login'] = AjaxErro("<b>TUDO CERTO: </b> Olá {$Read->getResult()[0]['user_name']}, Confira o Link Enviado Em Seu E-mail Para Recuperar Sua Senha!");
                    
                    $jSON['alert'] = ["green", "checkmark", "<b>TUDO CERTO</b>", "O Link Foi Enviado Para o Seu E-mail!"];
                    $jSON['redirect'] = './';
                endif;
            else:
                $jSON['alert'] = ["yellow", "warning", "<b>Olá </b>", "Informe Seu E-mail Para Recuperar a Senha!"];
            endif;
            break;

        case 'admin_newpass':
            if (empty($_SESSION['RecoverPass'])):
            else:
                if (in_array('', $PostData)):
                    $jSON['alert'] = ["blue", "warning", "<b>OPSSS</b>", "Para Redefinir Uma Nova Senha, Você Deve Informar e Repetir a Mesma Logo Senha Abaixo!"];
                elseif (strlen($PostData['user_password']) < 5):
                    $jSON['alert'] = ["yellow", "warning", "<b>ALERTA</b>", "Informe Uma Senha Com No Mínimo 5 Caracteres!"];
                elseif ($PostData['user_password'] != $PostData['user_password_re']):
                    $jSON['alert'] = ["yellow", "warning", "<b>ALERTA</b>", "Você Deve Informar e Repetir A Mesma Senha. Você Informou Senhas Diferentes!"];
                else:
                    $DecodeValidate = base64_decode($_SESSION['RecoverPass']);
                    parse_str($DecodeValidate, $Validate);

                    $Read = new Read;
                    $Read->FullRead("SELECT user_name, user_id FROM " . DB_USERS . " WHERE user_id = :id AND user_email = :email AND user_password = :pass", "id={$Validate['user_id']}&email={$Validate['user_email']}&pass={$Validate['user_password']}");
                    if ($Read->getResult()):
                        $UpdatePass = ['user_password' => hash('sha512', $PostData['user_password'])];
                        $Update = new Update;
                        $Update->ExeUpdate(DB_USERS, $UpdatePass, "WHERE user_id = :id", "id={$Read->getResult()[0]['user_id']}");

                        $_SESSION['trigger_login'] = AjaxErro("<b>ALERTA: </b> Olá {$Read->getResult()[0]['user_name']}, Para Logar, Informe Seu E-mail e Sua NOVA SENHA de Acesso!");
                        
                        $jSON['alert'] = ["green", "checkmark", "<b>TUDO CERTO</b>", "Sua Senha Foi Redefinida!"];
                        $jSON['redirect'] = './';
                    else:
                        $_SESSION['trigger_login'] = AjaxErro("<b>OPSSS: </b> Você Tentou Recuperar Sua Senha Com Um Código de Acesso Expirado!", E_USER_ERROR);
                        
                        $jSON['alert'] = ["red", "wondering2", "<b>ERRO</b>", "Não Foi Possível Redefinir Sua Senha!"];
                        $jSON['redirect'] = './';
                    endif;
                endif;
            endif;
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
