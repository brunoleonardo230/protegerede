<?php

$getPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (empty($getPost) || empty($getPost['action'])):
    die('Acesso Negado!');
endif;

$strPost = array_map('strip_tags', $getPost);
$POST = array_map('trim', $strPost);

$Action = $POST['action'];
unset($POST['action']);

$jSON = null;

usleep(2000);

require '../../../_app/Config.inc.php';
$Read = new Read;
$Create = new Create;
$Update = new Update;
$Delete = new Delete;
$Email = new Email;
$Trigger = new Trigger;

switch ($Action):
    //FORMULÁRIO DE CONTATO
    case 'contact_form':
        if (empty($POST['consultation_name']) || empty($POST['consultation_email'])):
            $jSON['wc_contact_error'] = "<p class='wc_contact_error'>&#10008; Por Favor, Preencha o Nome e E-mail Para Enviar a Mensagem!</p>";
        elseif (!Check::Email($POST['consultation_email']) || !filter_var($POST['consultation_email'], FILTER_VALIDATE_EMAIL)):
            $jSON['wc_contact_error'] = "<p class='wc_contact_error'>&#10008; O E-mail Informado Não Parece Válido. Por Favor, Informe o Seu E-mail!</p>";
        else:
            $MailContent = '
            <table width="550" style="font-family: "Trebuchet MS", sans-serif;">
             <tr><td>
              <font face="Trebuchet MS" size="3">
               #mail_body#
              </font>
              <p style="font-size: 0.875em;">
              <img src="' . BASE . '/admin/_img/mail.jpg" alt="Atenciosamente ' . SITE_NAME . '" title="Atenciosamente ' . SITE_NAME . '" /><br><br>
               ' . SITE_ADDR_NAME . '<br>Telefone: ' . SITE_ADDR_PHONE_A . '<br>E-mail: ' . SITE_ADDR_EMAIL . '<br><br>
               <a title="' . SITE_NAME . '" href="' . BASE . '">' . SITE_ADDR_SITE . '</a><br>' . SITE_ADDR_ADDR . '<br>'
                    . SITE_ADDR_CITY . '/' . SITE_ADDR_UF . ' - ' . SITE_ADDR_ZIP . '<br>' . SITE_ADDR_COUNTRY . '
              </p>
              </td></tr>
            </table>
            <style>body, img{max-width: 550px !important; height: auto !important;} p{margin-botton: 15px 0 !important;}</style>';

            //ENVIA PARA O CLIENTE
            $ToCliente = "
                    <p style='font-size: 1.2em;'>Prezado(a) {$POST['consultation_name']},</p>
                    <p><b>Obrigado pelo interesse em nossos serviços.</b></p>
                    <p>Este e-mail é para informar que recebemos sua solicitação de orçamento, e que estaremos respondendo o mais breve possível.</p>
                    <p><em>Atenciosamente " . SITE_NAME . ".</em></p>
            ";
            $MailMensagem = str_replace("#mail_body#", $ToCliente, $MailContent);
            $Email->EnviarMontando("Recebemos Sua Solicitação", $MailMensagem, SITE_ADDR_NAME, SITE_ADDR_EMAIL, $POST['consultation_name'], $POST['consultation_email']);

            $Telephone = (!empty($POST['consultation_telephone']) ? "<p><b>Telefone:</b> {$POST['consultation_telephone']}</p>" : null);
            $Date = (!empty($POST['date']) ? "<p><b>Data: </b>" .  date('d/m/Y', strtotime($POST['date'])) . "</p>" : null);
            $Time = (!empty($POST['time']) ? "<p><b>Data: </b>" .  date('H:i', strtotime($POST['time'])) . "</p>" : null);
            $Mensagem = (!empty($POST['consultation_message']) ? "<p><b>Mensagem: </b>" . nl2br($POST['consultation_message']) . "</p>" : null);

            //ENVIA PARA O ADMIN
            $ToAdmin = "
                    <p><b>Nome:</b> {$POST['consultation_name']}</p>
                    <p><b>E-mail:</b> {$POST['consultation_email']}</p>
                    $Telephone
                    $Date . ' - ' . $Time 
                    $Mensagem
                    <p style='font-size: 0.9em;'>
                        Enviada por: {$POST['consultation_name']}<br>
                        E-mail: {$POST['consultation_email']}<br>
                        Dia: " . date('d/m/Y H\hi') . "
                    </p>
            ";

            $CopyMensage = str_replace("#mail_body#", $ToAdmin, $MailContent);
            $Email->EnviarMontando("Marcação de Consulta", $CopyMensage, $POST['consultation_name'], $POST['consultation_email'], SITE_ADDR_NAME, SITE_ADDR_EMAIL);

            $CreateConsultation = [
                "consultation_name" => $POST['consultation_name'],
                "consultation_email" => $POST['consultation_email'],
                //"consultation_telephone" => $POST['consultation_telephone'],
                "date" => date('Y-m-d', strtotime(date('d/m/Y'))),
                //"time" => date('H:i', strtotime(date('h:i', time()))),
                "consultation_message" => $POST['consultation_message'],
                "consultation_status" => 1
            ];

            $Create->ExeCreate(DB_CONSULTATIONS, $CreateConsultation);

            $jSON['wc_send_mail'] = $POST['consultation_name'];
        endif;
        break;

    //MARCAÇÃO DE CONSULTA
    case 'schedule_form':
        if (empty($POST['consultation_name']) || empty($POST['consultation_email'])):
            $jSON['wc_schedule_error'] = "<p class='wc_schedule_error'>&#10008; Por Favor, Preencha o Nome, E-mail, Data e Hora Para Marcar Uma Consulta!</p>";
        elseif (!Check::Email($POST['consultation_email']) || !filter_var($POST['consultation_email'], FILTER_VALIDATE_EMAIL)):
            $jSON['wc_schedule_error'] = "<p class='wc_schedule_error'>&#10008; O E-mail Informado Não Parece Válido. Por Favor, Informe Seu E-mail!</p>";
        else:
            $MailContent = '
            <table width="550" style="font-family: "Trebuchet MS", sans-serif;">
             <tr><td>
              <font face="Trebuchet MS" size="3">
               #mail_body#
              </font>
              <p style="font-size: 0.875em;">
              <img src="' . BASE . '/admin/_img/mail.jpg" alt="Atenciosamente ' . SITE_NAME . '" title="Atenciosamente ' . SITE_NAME . '" /><br><br>
               ' . SITE_ADDR_NAME . '<br>Telefone: ' . SITE_ADDR_PHONE_A . '<br>E-mail: ' . SITE_ADDR_EMAIL . '<br><br>
               <a title="' . SITE_NAME . '" href="' . BASE . '">' . SITE_ADDR_SITE . '</a><br>' . SITE_ADDR_ADDR . '<br>'
                    . SITE_ADDR_CITY . '/' . SITE_ADDR_UF . ' - ' . SITE_ADDR_ZIP . '<br>' . SITE_ADDR_COUNTRY . '
              </p>
              </td></tr>
            </table>
            <style>body, img{max-width: 550px !important; height: auto !important;} p{margin-botton: 15px 0 !important;}</style>';

            //ENVIA PARA O CLIENTE
            $ToCliente = "
                    <p style='font-size: 1.2em;'>Prezado(a) {$POST['consultation_name']},</p>
                    <p><b>Obrigado pelo interesse em nossos serviços.</b></p>
                    <p>Este e-mail é para informar que recebemos sua solicitação de orçamento, e que estaremos respondendo o mais breve possível.</p>
                    <p><em>Atenciosamente " . SITE_NAME . ".</em></p>
            ";
            $MailMensagem = str_replace("#mail_body#", $ToCliente, $MailContent);
            $Email->EnviarMontando("Recebemos Sua Solicitação", $MailMensagem, SITE_ADDR_NAME, SITE_ADDR_EMAIL, $POST['consultation_name'], $POST['consultation_email']);

            $Telephone = (!empty($POST['consultation_telephone']) ? "<p><b>Telefone:</b> {$POST['consultation_telephone']}</p>" : null);
            $Date = (!empty($POST['date']) ? "<p><b>Data: </b>" .  date('d/m/Y', strtotime($POST['date'])) . "</p>" : null);
            $Mensagem = (!empty($POST['consultation_message']) ? "<p><b>Mensagem: </b>" . nl2br($POST['consultation_message']) . "</p>" : null);
            
            //ENVIA PARA O ADMIN
            $ToAdmin = "
                    <p><b>Nome:</b> {$POST['consultation_name']}</p>
                    <p><b>E-mail:</b> {$POST['consultation_email']}</p>
                    $Telephone
                    $Date
                    $Mensagem
                    <p style='font-size: 0.9em;'>
                        Enviada Por: {$POST['consultation_name']}<br>
                        E-mail: {$POST['consultation_email']}<br>
                        Dia: " . date('d/m/Y H\hi') . "
                    </p>
            ";
                        
            $CopyMensage = str_replace("#mail_body#", $ToAdmin, $MailContent);
            $Email->EnviarMontando("Marcação de Consulta", $CopyMensage, $POST['consultation_name'], $POST['consultation_email'], SITE_ADDR_NAME, SITE_ADDR_EMAIL);
            
            $CreateConsultation = [
                "consultation_name" => $POST['consultation_name'],
                "consultation_email" => $POST['consultation_email'],
                "consultation_telephone" => $POST['consultation_telephone'],
                "date" => date('Y-m-d', strtotime($POST['date'])),
                "time" => date('H:i', strtotime($POST['time'])),
                "consultation_message" => $POST['consultation_message'],
                "consultation_status" => 1
            ];
            
            $Create->ExeCreate(DB_CONSULTATIONS, $CreateConsultation);

            $jSON['wc_send_mail'] = $POST['consultation_name'];
        endif;
        break;

    //NEWSLETTER
    case 'newsletter':
        $EmailAdd = $POST['contact_email'];
        $Name = $POST['contact_name'];
        $Phone = $POST['contact_telephone'];

        if (!Check::Email($EmailAdd)):
            $jSON['notify'][] = $Trigger->notify('OPSSS!, Digite Um E-mail Válido!', 'red', 'fa fa-warning', 5000);
            $jSON['success'] = false;
        else:
            $Read->ExeRead(DB_CONTACTS, "WHERE contact_email = :email", "email={$EmailAdd}");
            if ($Read->getResult()):
                $UpdateContact = [
                    'contact_name' => $Name,
                    'contact_email' => $EmailAdd,
                    'contact_telephone' => $Phone
                ];
            
                $Update->ExeUpdate(DB_CONTACTS, $UpdateContact, "WHERE contact_id = :cid", "cid={$Read->getResult()[0]['contact_id']}");
                $jSON['notify'][] = $Trigger->notify('Atualizamos Seu Cadastro Em Nossa Lista de Contatos! ' . $Name . ' =)', 'blue', 'infinity_agency-check', 3000);
                $jSON['success'] = true;
            else:
                $CreateContact = [
                    'contact_name' => $Name,
                    'contact_email' => $EmailAdd,
                    'contact_telephone' => $Phone
                ];

                $Create->ExeCreate(DB_CONTACTS, $CreateContact);
                $jSON['notify'][] = $Trigger->notify('TUDO CERTO! Você Se Inscreveu Na Lista de Contatos Com Sucesso! =)', 'blue', 'infinity_agency-check', 3000);
                $jSON['success'] = true;
            endif;
        endif;
        break;
endswitch;

echo json_encode($jSON);
