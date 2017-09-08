<div class="wrap">
    <h1>Agencies</h1>
    <?php $agencies = State::get_agencies(); ?>
    <table id="example" class="display" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Agency id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>action</th>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach ($agencies as $key => $agency): ?>
                <tr>
                    <td><?php echo $agency['pf_agency_id']; ?></td>
                    <td><?php echo $agency['title']; ?></td>
                    <td><?php echo $agency['agency_email']; ?></td>
                    <td><?php echo $agency['agency_phone']; ?></td>
                    <td><?php echo Temp::agency_status_update_button($agency['pf_agency_id'], $agency['status']); ?></td>
                </tr>
            <?php endforeach; ?>

            
        </tbody>
    </table>
</div>