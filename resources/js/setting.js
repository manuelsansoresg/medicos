
if (document.getElementById('is_payment_transfer')) {
    document.addEventListener('DOMContentLoaded', function() {
        const transferCheck = document.getElementById('is_payment_transfer');
        const contentTransfer = document.getElementById('content-transfer');
        
        if (transferCheck) {
            transferCheck.addEventListener('change', function() {
                contentTransfer.style.display = this.checked ? 'block' : 'none';
            });
        }
    });
}

$("#frm-setting").submit(function (e) {
    e.preventDefault();
    const form = document.getElementById("frm-setting");
    const data = new FormData(form);

    axios
        .post("/admin/setting", data)
        .then(function (response) {
            window.location = '/admin/setting';
        })
        .catch(e => { });
});
