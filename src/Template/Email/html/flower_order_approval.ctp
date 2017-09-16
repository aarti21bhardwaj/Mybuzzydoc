<div>
    <h2>Order Summary</h2>
    <br>
    <div>
        <div>
            <table>
                <thead>
                <tr>
                    <th>Item List</th>
                    <th>Flower</th>
                    <th>Price</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><div><strong>Flower for  <?= $orderName?></strong></div>
                        <small>Product Code: <?= $productCode ?>.</small></td>
                    <td><img src="<?= $imgLink ?>"></td>
                    <td>$<?= $price ?></td>
                </tr>
                </tbody>
            </table>
            <hr>
            <table style="margin-left:40%;">
                <tbody>
                <tr>
                    <td><strong>Sub Total :</strong></td>
                    <td>$<?= $sub_total ?></td>
                </tr>
                <tr>
                    <td><strong>Service Charge :</strong></td>
                    <td>$<?= $serviceCharge ?></td>
                </tr>
                <tr>
                    <td><strong>Taxes :</strong></td>
                    <td>$<?= $taxes ?></td>
                </tr>   
                <tr>
                    <td><strong>TOTAL :</strong></td>
                    <td><strong>$<?= $total ?></strong></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <br>
    To make payment for the order click the following link: 
    <br>
    <?= $link ?>
</div>