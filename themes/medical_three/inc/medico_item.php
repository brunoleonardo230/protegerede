<div class="col-md-12">
    <div class="team-content">
        <div class="team-box">
            <img src="<?= BASE; ?>/tim.php?src=uploads/<?= $doctor_cover; ?>&w=240&h=270" title="<?= $doctor_name; ?>" alt="<?= $doctor_name; ?>"/>
            <h5><?= $doctor_name; ?></h5>
        </div>
        <span class="team-catagory"><?= getSpecialtiesDoctors($doctor_specialty); ?></span>
    </div>
</div>