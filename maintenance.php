<style>
.workcontrol_maintenance_content{
    display: block;
    position: fixed;
    width: 100%;
    height: 100%;
}

/************************************
#### GRADIENTES BACKGROUND LOGIN ####
************************************/
.workcontrol_maintenance_content.blue{
    background: rgb(2,0,36); background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(52,104,215,1) 0%, rgba(0,212,255,1) 100%);    
}

.workcontrol_maintenance_content.darkblue{
    background: rgb(0,0,102); background: linear-gradient(90deg, rgba(0,0,102,1) 0%, rgba(0,0,180,1) 100%);    
}

.workcontrol_maintenance_content.green{
    background: rgb(10,134,55); background: linear-gradient(90deg, rgba(10,134,55,1) 0%, rgba(24,202,23,1) 100%);    
}

.workcontrol_maintenance_content.darkgreen{
    background: rgb(0,80,30); background: linear-gradient(90deg, rgba(0,80,30,1) 0%, rgba(0,137,51,1) 100%);    
}

.workcontrol_maintenance_content.yellow{
    background: rgb(244,192,35); background: linear-gradient(90deg, rgba(244,192,35,1) 0%, rgba(255,219,110,1) 100%);   
}

.workcontrol_maintenance_content.orange{
    background: rgb(255,128,0); background: linear-gradient(90deg, rgba(255,128,0,1) 0%, rgba(255,166,77,1) 100%);    
}

.workcontrol_maintenance_content.red{
    background: rgb(197,13,13); background: linear-gradient(90deg, rgba(197,13,13,1) 0%, rgba(241,37,37,1) 100%);  
}

.workcontrol_maintenance_content.darkred{
    background: rgb(72,4,4); background: linear-gradient(90deg, rgba(72,4,4,1) 0%, rgba(125,0,0,1) 100%);    
}

.workcontrol_maintenance_content.pink{
    background: rgb(255,0,127); background: linear-gradient(90deg, rgba(255,0,127,1) 0%, rgba(255,119,187,1) 100%); 
}

.workcontrol_maintenance_content.purple{
    background: rgb(80,0,80); background: linear-gradient(90deg, rgba(80,0,80,1) 0%, rgba(173,0,173,1) 100%);     
}

.workcontrol_maintenance_content.grey{
    background: rgb(102,102,102); background: linear-gradient(90deg, rgba(102,102,102,1) 0%, rgba(153,153,153,1) 100%); 
}

.workcontrol_maintenance_content.dark{
    background: rgb(0,0,0); background: linear-gradient(90deg, rgba(0,0,0,1) 0%, rgba(0,0,0,0.6) 100%);     
}

.workcontrol_maintenance_content.light{
    background: rgb(247,247,247); background: linear-gradient(90deg, rgba(247,247,247,1) 0%, rgba(242,242,242,1) 100%);     
}

.workcontrol_maintenance_content.purple_brown{
    background: rgb(100,51,96); background: linear-gradient(90deg, rgba(100,51,96,1) 0%, rgba(113,52,94,1) 25%, rgba(148,51,68,1) 75%, rgba(170,68,46,1) 100%);
}

.workcontrol_maintenance_content.purple_yellow{
    background: rgb(131,58,180); background: linear-gradient(90deg, rgba(131,58,180,1) 0%, rgba(253,29,29,1) 50%, rgba(252,176,69,1) 100%);     
}

.workcontrol_maintenance_content.blue_radial{
    background: rgb(252,70,107); background: radial-gradient(circle, rgba(252,70,107,1) 0%, rgba(63,94,251,1) 100%);     
}

.workcontrol_maintenance_content.pink_radial{
    background: rgb(148,187,233); background: radial-gradient(circle, rgba(148,187,233,1) 0%, rgba(238,174,202,1) 100%);     
}

.workcontrol_maintenance_content .maintenance_box{
    display: block;
    width: 600px;
    margin: 10% auto;
    max-width: 90%;
    background: #fff;
    padding: 50px;
}

.workcontrol_maintenance_content .maintenance_box h1{
    font-size: 2em;
    font-weight: 600;
    text-shadow: 1px 1px 0 #eee;
}

/************************************
###### FONT COLOR MAINTENANCE #######
************************************/
.workcontrol_maintenance_content .maintenance_box h1.blue {
    color: #0E96E5;
}

.workcontrol_maintenance_content .maintenance_box h1.darkblue {
    color: #000066;
}

.workcontrol_maintenance_content .maintenance_box h1.green {
    color: #0A8637;
}

.workcontrol_maintenance_content .maintenance_box h1.darkgreen {
    color: #00501E;
}

.workcontrol_maintenance_content .maintenance_box h1.yellow {
    color: #F4C023;
}

.workcontrol_maintenance_content .maintenance_box h1.orange {
    color: #FF8000;
}

.workcontrol_maintenance_content .maintenance_box h1.red {
    color: #C50D0D;
}

.workcontrol_maintenance_content .maintenance_box h1.darkred {
    color: #480404;
}

.workcontrol_maintenance_content .maintenance_box h1.pink {
    color: #FF007F;
}

.workcontrol_maintenance_content .maintenance_box h1.purple {
    color: #500050;
}

.workcontrol_maintenance_content .maintenance_box h1.grey {
    color: #666666;
}

.workcontrol_maintenance_content .maintenance_box h1.dark {
    color: #000000;
}

.workcontrol_maintenance_content .maintenance_box h1.purple_brown {
    color: #643360;
}

.workcontrol_maintenance_content .maintenance_box h1.purple_yellow {
    color: #833AB4;
}

.workcontrol_maintenance_content .maintenance_box h1.blue_radial {
    color: #3F5EFB;
}

.workcontrol_maintenance_content .maintenance_box h1.pink_radial {
    color: #94BBFF;
}

.workcontrol_maintenance_content .maintenance_box p{
    margin: 15px 0;
}
</style>
<article class="workcontrol_maintenance_content blue">
    <div class="maintenance_box">
        <h1 class="blue">Desculpe, Estamos Em Manutenção!</h1>
        <p>Neste Momento Estamos Trabalhando Para Melhorar Ainda Mais Sua Experiência Em Nosso Site.</p>
        <p><b>Por Favor, Volte Em Algumas Horas Para Conferir As Novidades!</b></p>
        <em>Atenciosamente <?= SITE_NAME; ?></em>
    </div>
</article>
