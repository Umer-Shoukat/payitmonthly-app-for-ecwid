window.onload = function () {
  Ecwid.OnPageLoad.add(function (page) {
    setTimeout(() => addDetails(page), 500);
  });

  function addDetails(page) {
    if (page.type == "PRODUCT") {
      const p_price = +document
        .querySelector(".ec-price-item")
        .getAttribute("content");
      const installment = p_price / 12;
      let round_value = Math.round((installment + Number.EPSILON) * 100) / 100;
      const productPriceTaxes = document.querySelectorAll(
        ".product-details__product-price-taxes"
      );
      // Create a new div element
      const newDiv = document.createElement("p");
      newDiv.classList.add("payitMonthly-banner");
      // Add some content to the new div
      newDiv.innerText = `Available on finance from £ ${round_value} per month`;
      //console.log(newDiv);
      // Insert the new div element after the "product-details__product-price-taxes" element
      for (let i = 0; i < productPriceTaxes.length; i++) {
        //console.log(productPriceTaxes[i])
        productPriceTaxes[i].appendChild(newDiv);
      }
    }

    if (page.type == "CART" || page.type === "CHECKOUT_PAYMENT_DETAILS") {
      const p_price = +document
        .querySelector(".ec-cart-summary__total")
        .innerText.replace("£", "");
      const installment = p_price / 12;
      let round_value = Math.round((installment + Number.EPSILON) * 100) / 100;

      const productPriceTaxes = document.querySelectorAll(
        ".ec-cart__sidebar-inner"
      );
      // Create a new div element
      const newDiv = document.createElement("p");
      newDiv.classList.add("payitMonthly-banner");
      // Add some content to the new div
      newDiv.innerText = `Available on finance from £ ${round_value} per month`;
      //console.log(newDiv);
      // Insert the new div element after the "product-details__product-price-taxes" element
      for (let i = 0; i < productPriceTaxes.length; i++) {
        //console.log(productPriceTaxes[i])
        productPriceTaxes[i].appendChild(newDiv);
      }
    }
  }
};
