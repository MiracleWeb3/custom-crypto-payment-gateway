<?php

defined('ABSPATH') || exit;

class Empty_Payment_Gateway extends WC_Payment_Gateway {

    public function __construct() {
        $this->id = 'empty_payment';
        $this->method_title = __('Empty Payment', 'text-domain');
        $this->method_description = __('This is a test payment gateway that does nothing.', 'text-domain');
        $this->has_fields = true;

        // Load the settings
        $this->init_form_fields();
        $this->init_settings();

        // Define user settings
        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');

        // Save settings
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title'   => __('Enable/Disable', 'text-domain'),
                'type'    => 'checkbox',
                'label'   => __('Enable Empty Payment Gateway', 'text-domain'),
                'default' => 'no',
            ),
            'title' => array(
                'title'       => __('Title', 'text-domain'),
                'type'        => 'text',
                'description' => __('The title displayed to the customer during checkout.', 'text-domain'),
                'default'     => __('Empty Payment', 'text-domain'),
                'desc_tip'    => true,
            ),
            'description' => array(
                'title'       => __('Description', 'text-domain'),
                'type'        => 'textarea',
                'description' => __('The description displayed to the customer during checkout.', 'text-domain'),
                'default'     => __('This is a test payment gateway that does nothing.', 'text-domain'),
                'desc_tip'    => true,
            ),
        );
    }


    public function payment_fields() {
        echo <<<HTML
        <style>    .network-selection-container {
        text-align: center;
        margin-top: 40px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .network-selection-container h2 {
        font-size: 1.8em;
        font-weight: bold;
        color: #333;
        margin-bottom: 30px;
    }

    .network-options {
        display: flex;
        justify-content: center;
        gap: 20px;
    }

    .network-option-container {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .network-option {
        width: 100px;
        height: 100px;
        border: 3px solid #000;
        outline: none;
        cursor: pointer;
        position: relative;
        z-index: 0;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .placeholder-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #ccc;
        color: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2em;
    }

    .network-option.selected .placeholder-icon {
        animation: iconCircularMotion 3s infinite linear; /* Плавная круговая анимация */
    }

    .network-option.selected {
        transform: scale(1.1);
        opacity: 1;
    }

    /* Анимация сияния для каждого элемента */
    .network-option.ethereum.selected:before {
        content: '';
        background: linear-gradient(45deg, #e0e0e0, #333, #aaa, #000);
        position: absolute;
        top: -4px;
        left: -4px;
        width: calc(100% + 8px);
        height: calc(100% + 8px);
        background-size: 400%;
        z-index: -1;
        filter: blur(5px);
        border-radius: 15px;
        animation: glowing 20s linear infinite;
    }

    .network-option.tron.selected:before {
        content: '';
        background: linear-gradient(45deg, #ff0000, #ff3333, #990000, #660000);
        position: absolute;
        top: -4px;
        left: -4px;
        width: calc(100% + 8px);
        height: calc(100% + 8px);
        background-size: 400%;
        z-index: -1;
        filter: blur(5px);
        border-radius: 15px;
        animation: glowing 20s linear infinite;
    }

    .network-option.solana.selected:before {
        content: '';
        background: linear-gradient(45deg, #48cae4, #000, #7a00ff, #00d4ff);
        position: absolute;
        top: -4px;
        left: -4px;
        width: calc(100% + 8px);
        height: calc(100% + 8px);
        background-size: 400%;
        z-index: -1;
        filter: blur(5px);
        border-radius: 15px;
        animation: glowing 20s linear infinite;
    }

    .network-option:not(.selected) {
        opacity: 0.3;
    }

    .network-option-label {
        font-weight: 600;
        font-size: 1em;
        color: #333;
        margin-top: 10px;
        text-align: center;
    }

    .icon {
        width: 50px;
        height: 50px;
        transition: transform 0.3s ease;
    }

    .network-option.selected .icon {
        animation: iconCircularMotion 3s infinite linear;
    }

    /* Анимация для движущегося сияния */
    @keyframes glowing {
        0% { background-position: 0 0; }
        50% { background-position: 400% 0; }
        100% { background-position: 0 0; }
    }

    /* Круговое движение с плавным увеличением */
    @keyframes iconCircularMotion {
        0% { transform: translate(0, 0) scale(1); }
        25% { transform: translate(8px, -8px) scale(1.1); }
        50% { transform: translate(0, -12px) scale(1); }
        75% { transform: translate(-8px, -8px) scale(1.1); }
        100% { transform: translate(0, 0) scale(1); }
    }</style>
    <div class="network-selection-container">
            <h2>Choose Network</h2>
            <div class="network-options">
                <div class="network-option-container">
                    <div class="network-option ethereum" data-network="ethereum">
                        <img src="https://rilvora.com/wp-content/uploads/2024/11/ethereum-icon.png" alt="Ethereum Icon" class="icon">
                    </div>
                    <div class="network-option-label">Ethereum</div>
                </div>
                
                <div class="network-option-container">
                    <div class="network-option tron" data-network="tron">
                        <img src="https://rilvora.com/wp-content/uploads/2024/11/tron-icon.png" alt="Tron Icon" class="icon">
                    </div>
                    <div class="network-option-label">Tron</div>
                </div>
                
                <div class="network-option-container">
                    <div class="network-option solana" data-network="solana">
                        <img src="https://rilvora.com/wp-content/uploads/2024/11/solana-icon.png" alt="Solana Icon" class="icon">
                    </div>
                    <div class="network-option-label">Solana</div>
                </div>
            </div>
        </div>
        <script>document.addEventListener("DOMContentLoaded", function() {
    const options = document.querySelectorAll(".network-option");

    options.forEach(option => {
        option.addEventListener("click", function() {
            if (this.classList.contains("selected")) {
                this.classList.remove("selected");
            } else {
                options.forEach(opt => opt.classList.remove("selected"));
                this.classList.add("selected");
            }
        });
    });
});
</script>
HTML;
}

    public function process_payment($order_id) {
        $order = wc_get_order($order_id);

        // Mark as on-hold
        $order->update_status('on-hold', __('Awaiting empty payment', 'text-domain'));

        // Reduce stock levels (optional)
        wc_reduce_stock_levels($order_id);

        // Return success and redirect to the thank you page
        return array(
            'result'   => 'success',
            'redirect' => $this->get_return_url($order),
        );
    }
}
