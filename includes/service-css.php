<style>
    /* Scoped styles for service pages if possible, or just the styles as they were */
    /* Note: These styles were originally in product-photography.php. 
       Be careful if they affect global elements like body or container. 
       However, since they were applied to the page globally, we keep them here. */

    /* body {
        font-family: Arial, sans-serif;
    } */

    /* .ul is a bit generic, checking if it conflicts */
    .ul {
        line-height: 50px;
        color: var(--text-color2);
    }

    /* .container is definitely generic. The theme probably has its own container. 
       But the original file overrode it. */
    .container {
        width: 90%;
        max-width: 1200px;
        margin: auto;
        padding: 20px 0;
    }

    .section-title {
        text-align: center;
        font-size: 2em;
        margin-bottom: 20px;
        color: #333;
    }

    .card-container {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: nowrap;
    }

    .card {
        background: white;
        padding: 20px;
        width: 23%;
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .card h3 {
        color: #444;
    }

    .card p {
        color: #666;
        font-size: 14px;
    }

    .card .price {
        font-size: 18px;
        font-weight: bold;
        color: #ff5733;
        margin-top: 10px;
    }

    .card ul {
        list-style-type: none;
        padding: 0;
        flex-grow: 1; /* Allow the list to take available space */
    }

    .card ul li {
        margin: 5px 0;
    }

    .book-slot {
        display: inline-block;
        margin-top: auto; /* Push to bottom */
        padding: 10px 15px;
        background-color: #ff5733;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    /* FAQ Styles */
    .faq-title {
        color: #ff7f50;
        font-family: var(--font-cormorant);
        margin-bottom: 20px;
        text-align: left;
        margin-left: 5%;
    }

    .faq-list {
        list-style: none;
        padding: 0;
        margin-left: 5%;
        text-align: left;
    }

    .faq-list li {
        color: red;
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 15px;
        line-height: 1.5;
    }

    .faq-list li span.star {
        margin-right: 5px;
    }

    .book-slot:hover {
        background-color: #e04e2a;
    }

    
    @media (max-width: 768px) {
        .card-container {
            flex-direction: column;
            align-items: center;
        }

        .card {
            width: 100%;
        }

        .ul {
            line-height: 30px;
            font-size: 15px;
        }

        .section-title {
            font-size: 1.5em;
        }
    }
</style>
