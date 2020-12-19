<?php class_exists('Kernel\Template') or exit; ?>
<style>
    #fields {
        display: flex;
    }

    /* Create three column of equal width */
    .columns {
        width: 200px;
    }

    /* Style the list */
    .price {
        list-style-type: none;
        border: 1px solid #eee;
        margin: 0;
        padding: 0;
        -webkit-transition: 0.3s;
        transition: 0.3s;
    }

    /* Add shadows on hover */
    .price:hover {
        box-shadow: 0 8px 12px 0 rgba(0,0,0,0.2)
    }

    /* Pricing header */
    .price .header {
        background-color: #c10303;
        color: white;
        font-size: 25px;
    }

    /* List items */
    .price li {
        border-bottom: 1px solid #fff;
        background-color: white;
        padding: 20px;
        text-align: center;
    }

    /* Grey list item */
    .price .grey {
        background-color: #eee;
        font-size: 20px;
    }

    /* The "Sign Up" button */
    .button {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 10px 25px;
        text-align: center;
        text-decoration: none;
        font-size: 18px;
    }

    /* Change the width of the three columns to 100%
    (to stack horizontally on small screens) */
    @media only screen and (max-width: 600px) {
        .columns {
            width: 100%;
        }
    }
</style>

<h1><?php echo $title ?></h1>

<div id="fields">
    <?php foreach($columns as $column => $values): ?>

    <div class="columns">
        <ul class="price">
            <li class="header"><?php echo $values["header"] ?></li>
            <li class="grey"><?php echo $values["grey"] ?></li>

            <?php foreach($values["li"] as $li): ?>
                <li><?php echo $li ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php endforeach; ?>
</div>
