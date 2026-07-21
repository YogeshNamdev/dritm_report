<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM AI Remark Enhancer</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>includes/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>includes/dist/css/adminlte.min.css">
    <style>
        body { background: #f4f6f9; font-family: Arial, sans-serif; }
        .ai-card { max-width: 860px; margin: 48px auto; }
        .remark-output { white-space: pre-wrap; font-size: 16px; line-height: 1.6; }
        .result-box { display: none; border: 1px solid #d7e7d8; background: #f4fbf4; border-radius: 6px; padding: 16px; }
        .error-box { display: none; border: 1px solid #f1c7c7; background: #fff5f5; border-radius: 6px; padding: 12px; color: #8a1f1f; }
    </style>
</head>
<body>
<div class="container">
    <div class="card ai-card">
        <div class="card-header">
            <h3 class="card-title">AI Remark Enhancer</h3>
        </div>
        <div class="card-body">
            <form id="aiRemarkForm" action="<?php echo base_url('AiGroq'); ?>" method="POST">
                <div class="form-group">
                    <label for="remark">Officer Remark</label>
                    <textarea class="form-control" id="remark" name="remark" rows="6" required><?php echo isset($raw_remark) ? htmlspecialchars($raw_remark, ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                </div>
                <button type="submit" id="enhanceBtn" name="submit" value="1" class="btn btn-primary">
                    <i class="fas fa-magic"></i> Enhance Remark
                </button>
            </form>

            <div id="errorBox" class="error-box mt-3"><?php echo !empty($error_message) ? htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') : ''; ?></div>

            <div id="resultBox" class="result-box mt-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>Enhanced Remark</strong>
                    <button type="button" class="btn btn-sm btn-secondary" id="copyBtn">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                </div>
                <div id="finalRemark" class="remark-output"><?php echo !empty($enhanced_remark) ? htmlspecialchars($enhanced_remark, ENT_QUOTES, 'UTF-8') : ''; ?></div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>includes/plugins/jquery/jquery.min.js"></script>
<script>
(function() {
    var endpoint = <?php echo json_encode(base_url('AiGroq/enhance_remark')); ?>;
    var initialResult = <?php echo json_encode(!empty($enhanced_remark)); ?>;
    var initialError = <?php echo json_encode(!empty($error_message)); ?>;

    if(initialResult) {
        $('#resultBox').show();
    }
    if(initialError) {
        $('#errorBox').show();
    }

    $('#aiRemarkForm').on('submit', function(e) {
        e.preventDefault();

        var remark = $.trim($('#remark').val());
        if(remark === '') {
            showError('Remark is required.');
            return;
        }

        setLoading(true);
        $('#errorBox').hide().text('');
        $('#resultBox').hide();

        $.ajax({
            url: endpoint,
            type: 'POST',
            dataType: 'json',
            data: { remark: remark },
            success: function(data) {
                if(data && data.response === true && data.enhanced_remark) {
                    $('#finalRemark').text(data.enhanced_remark);
                    $('#resultBox').show();
                } else {
                    showError(data && data.message ? data.message : 'AI service did not return a valid response.');
                }
            },
            error: function(xhr) {
                var message = 'Unable to enhance remark. Please try again.';
                if(xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showError(message);
            },
            complete: function() {
                setLoading(false);
            }
        });
    });

    $('#copyBtn').on('click', function() {
        var text = $('#finalRemark').text();
        if(navigator.clipboard) {
            navigator.clipboard.writeText(text);
            return;
        }

        var temp = $('<textarea>');
        $('body').append(temp);
        temp.val(text).select();
        document.execCommand('copy');
        temp.remove();
    });

    function setLoading(isLoading) {
        $('#enhanceBtn').prop('disabled', isLoading).html(
            isLoading ? '<i class="fas fa-spinner fa-spin"></i> Enhancing...' : '<i class="fas fa-magic"></i> Enhance Remark'
        );
    }

    function showError(message) {
        $('#errorBox').text(message).show();
    }
})();
</script>
</body>
</html>
