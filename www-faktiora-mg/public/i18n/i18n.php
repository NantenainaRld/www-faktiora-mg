<script>
    const lang = {
        user: "<?= __('forms.labels.user') ?>",
        male: "<?= __('forms.labels.male_m') ?>",
        female: "<?= __('forms.labels.female_f') ?>",
        effective_client: "<?= __('forms.titles.effective_client') ?>",
        effective_user: "<?= __('forms.titles.effective_user') ?>",
        effective_client_user: "<?= __('forms.titles.effective_client_user') ?>",
        admin: "<?= ucfirst(__('forms.labels.admin')) ?>",
        cashier: "<?= ucfirst(__('forms.labels.cashier')) ?>",
        effective_user_role: "<?= ucfirst(__('forms.titles.effective_user_role')) ?>",
        clients_users: "<?= __('forms.labels.clients_users') ?>",
        autre_entree: "<?= __('forms.labels.autre_entree') ?>",
        bill: "<?= ucfirst(__('forms.labels.bill')) ?>",
        outflow: "<?= ucfirst(__('forms.labels.outflow')) ?>",
        nb_transactions: "<?= ucfirst(__('forms.labels.nb_transactions')) ?>",
        total_transactions: "<?= ucfirst(__('forms.labels.total_transactions')) ?>",
    }
    //cookie lang value
    const cookieLangValue = document.cookie
        .split('; ')
        .find(row => row.startsWith(`lang=`))
        ?.split('=')[1];
</script>