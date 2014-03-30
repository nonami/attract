<?php $span_value = ($use_sidebar == TRUE ? "col-md-9" : "col-md-12"); ?>

<div class="<?php echo $span_value ?>">
    <?php
    if ($this->session->flashdata('message') != '') {
        $flashdata = $this->session->flashdata('message');
    }
    ?>

    <?php if (isset($flashdata)): ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php echo $flashdata ?>
        </div>
        <?php endif ?>

    <h2><?php echo $title ?></h2>


    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped" id="users">
        <thead>
            <tr>
                <th>Status ID</th>
                <th>Status name</th>
                <th>Status color</th>
            </tr>
        </thead>
        <tbody>

<?php foreach ($statuses as $status): ?>
                <tr>
                    <td><?php echo $status['status_id'] ?></td>
                    <td><a href="#" status="<?php echo site_url('/statuses/edit/' . $status['status_id']) ?>" data-toggle="modal"><?php echo $status['status_name'] ?></a></td>
                    <td><span class="label label-default color-label" style="background-color:<?php echo $status['status_color'] ?>"><?php echo $status['status_color'] ?></span></td>
                </tr>
<?php endforeach ?>

        </tbody>
        <tfoot>
            <tr>
                <th>Status ID</th>
                <th>Status name</th>
                <th>Status color</th>
            </tr>
        </tfoot>
    </table>

    <a class="btn btn-default btn-lg btn-block" data-toggle="modal" status="<?php echo site_url('/statuses/create') ?>" href="#">Add status</a>
</div><!--/span-->


<script>
    $(document).ready(function() {
        // Support for AJAX loaded modal window.
        // Focuses on first input textbox after it loads the window.
        $('[data-toggle="modal"]').click(function(e) {
            e.preventDefault();
            var url = $(this).attr('status');
            if (url.indexOf('#') == 0) {
                $(url).modal('open');
            } else {
                $.get(url, function(data) {
                    $('<div class="modal fade">' + data + '</div>').modal();
                }).success(function() {
                    $('input:text:visible:first').focus();
                });
            }
        });
    });
</script>