Ödəmə səhifəsinə yönləndirilirsiniz...

<form action='https://www.portmanat.az/checkout' method='post' id="portmanat-checkout" style="display: none">
    <input type='hidden' name='s_id' value='<?= Yii::$app->params["portmanat_service_id"]?>'>
    <input type='hidden' name='o_id' value='<?= $order_id; ?>'>
    <input type='hidden' name='method' value='<?= $method?>'>
    <?php
    if($method == 'account'){
        echo "<input type='text' name='amount' value='".$amount."'>";
    }

    ?>
    <input type='submit' value='Portmanat Kodla ödə'>
</form>
<script type="text/javascript">
    document.getElementById("portmanat-checkout").submit();
</script>
