$(document).ready(function () {
  // Met à jour les index des lignes
  function updateIndex() {
    $("#devisTable tbody tr").each(function (index) {
      $(this)
        .find(".index")
        .text(index + 1);
    });
  }

  // Ajout d'une nouvelle ligne
  $("#addRow").click(function () {
    let newRow = `
            <tr>
                <td class="index"></td>
                <td><input type="text" class="form-control" name="designation[]" placeholder="Désignation"></td>
                <td><input type="number" class="form-control prix" name="prix[]" placeholder="Prix"></td>
                <td><input type="number" class="form-control quantite" name="quantite[]" placeholder="Quantité"></td>
                <td><input type="number" class="form-control tva" name="tva[]" placeholder="TVA"></td>
                <td><input type="number" class="form-control remise" name="remise[]" placeholder="Remise"></td>
                <td><input type="number" class="form-control total" name="total[]" readonly></td>
                <td><button type="button" class="btn btn-danger remove-row">-</button></td>
            </tr>
        `;
    $("#devisTable tbody").append(newRow);
    updateIndex();
  });

  // Suppression d'une ligne
  $(document).on("click", ".remove-row", function () {
    $(this).closest("tr").remove();
    calculateTotals();
    updateIndex();
  });

  // Calcul du total par ligne et totaux globaux
  $(document).on("input", ".prix, .quantite, .tva, .remise", function () {
    let row = $(this).closest("tr");
    let prix = parseFloat(row.find(".prix").val()) || 0;
    let quantite = parseFloat(row.find(".quantite").val()) || 0;
    let tva = parseFloat(row.find(".tva").val()) || 0;
    let remise = parseFloat(row.find(".remise").val()) || 0;

    let total =
      prix * quantite +
      prix * quantite * (tva / 100) -
      prix * quantite * (remise / 100);
    row.find(".total").val(total.toFixed(2));

    calculateTotals();
  });

  // Masquer la zone TVA au départ
  $(".tvaZone").hide();

  // Afficher/Masquer la zone TVA selon la case à cocher
  $("#tvaFacturable").change(function () {
    let tvaFacturable = $(this).is(":checked");
    if (tvaFacturable) {
      $(".tvaZone").show();
    } else {
      $(".tvaZone").hide();
    }
    calculateTotals();
  });

  // Calcul des totaux globaux
  function calculateTotals() {
    let totalHT = 0;
    let totalTTC = 0;
    let tvaTotal = 0;
    let tvaFacturable = $("#tvaFacturable").is(":checked") ? 1 : 0;

    $("#devisTable tbody tr").each(function () {
      let prix = parseFloat($(this).find(".prix").val()) || 0;
      let quantite = parseFloat($(this).find(".quantite").val()) || 0;
      let tva = parseFloat($(this).find(".tva").val()) || 0;
      let remise = parseFloat($(this).find(".remise").val()) || 0;

      let totalHTLine = prix * quantite;
      let totalTTCLine;

      if (tvaFacturable) {
        totalTTCLine =
          totalHTLine +
          totalHTLine * (tva / 100) -
          totalHTLine * (remise / 100);
        tvaTotal += totalHTLine * (tva / 100);
      } else {
        totalTTCLine = totalHTLine - totalHTLine * (remise / 100);
      }

      $(this).find(".total").val(totalTTCLine.toFixed(2));
      totalHT += totalHTLine;
      totalTTC += totalTTCLine;
    });

    $("#totalHT").val(totalHT.toFixed(2));
    $("#totalTTC").val(totalTTC.toFixed(2));
    $("#tva").val(tvaTotal.toFixed(2));
  }

  // Prévisualisation du logo
  $("#logoUpload").change(function () {
    let reader = new FileReader();
    reader.onload = function (e) {
      $("#logoPreview").attr("src", e.target.result).show();
      $("#logoMessage").hide();
    };
    if (this.files[0]) {
      reader.readAsDataURL(this.files[0]);
    }
  });

  // Drag and Drop pour le logo
  $("#logoUploadContainer")
    .on("dragover", function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).addClass("drag-over");
    })
    .on("dragleave", function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).removeClass("drag-over");
    })
    .on("drop", function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).removeClass("drag-over");
      let files = e.originalEvent.dataTransfer.files;
      if (files.length > 0) {
        $("#logoUpload").prop("files", files);
        let reader = new FileReader();
        reader.onload = function (e) {
          $("#logoPreview").attr("src", e.target.result).show();
          $("#logoMessage").hide();
        };
        reader.readAsDataURL(files[0]);
      }
    });

  // Initialisation
  updateIndex();
  calculateTotals();
});
