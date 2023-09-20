<?php
  require_once 'database.php';
?>

<!doctype html>
<html lang="pt-br">
  <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema de Consultas</title>

  <!-- CSS Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

  <!-- CSS DataTables -->
  <link href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/datatables.min.css" rel="stylesheet">
  </head>
  <body>
<main>

<div class="container">

  <header class="d-flex align-items-center pb-2 my-2 border-bottom">
      <span class="fs-4">Formulário</span>
  </header>

  <form action="" method="post">
    <div class="row">
      <div class="col-md-12 my-1">
        <label for="nome">Nome:</label>
        <!-- Input para preencher o nome -->
        <input type="text" id="nome" name="nome" class="form-control" placeholder="Digite o nome" value="<?= $_POST["nome"] ?>"></input>
      </div>
      <div class="col-md-6 my-1">
        <label for="sexo">Sexo:</label>
        <select id="sexo" name="sexo" class="form-control">
          <option disabled selected>Selecione</option>
          <?php
            // Array de opções do select, ele irá relembrar a opção selecionada após o submit do form
            $values = array('F' => 'Feminino', 'M' => 'Masculino');
            foreach($values as $val => $value) {
              $selected = (!empty($_POST['sexo']) && $_POST['sexo'] == $val) ? 'selected' : '';
              echo "<option value='$val' $selected>$value</option>";
            }
          ?>
        </select>
      </div>
      <div class="col-md-6 my-1">
        <label for="cargo">Cargo:</label>
        <select id="cargo" name="cargo" class="form-control">
          <option disabled selected>Selecione</option>
          <?php
            // Array de opções do select, ele irá relembrar a opção selecionada após o submit do form
            $values = array('Presidente' => 'Presidente', 'Gerente' => 'Gerente', 'Funcionário' => 'Funcionário');
            foreach($values as $val => $value) {
              $selected = (!empty($_POST['cargo']) && $_POST['cargo'] == $val) ? 'selected' : '';
              echo "<option value='$val' $selected>$value</option>";
            }
          ?>
        </select>
      </div>
      <div class="col-md-12 my-2">
        <button type="submit" name="submit" class="btn btn-primary">Pesquisar</button>
      </div>
    </div>
  </form>

  <?php if (isset($_POST["submit"])) { 

  // Faz as validações dos inputs
  // Essa validação nos permitirá que os campos do form não sejam obrigatórios
  if (!empty($_POST["nome"])) {
    $nomeQuery = "nome LIKE '%{$_POST["nome"]}%'";
  } else {
    $nomeQuery = "nome IS NOT NULL";
  }
  
  if (!empty($_POST["sexo"])) {
    $sexoQuery = " AND sexo LIKE '%{$_POST["sexo"]}%'";
  } else {
    $sexoQuery = " AND sexo IS NOT NULL";
  }

  if (!empty($_POST["cargo"])) {
    $cargoQuery = " AND cargo LIKE '%{$_POST["cargo"]}%'";
  } else {
    $cargoQuery = " AND cargo IS NOT NULL";
  }

  // Realiza a busca das informações no banco de dados
  try {
    $db = Database::getInstance();
    $con = $db->getConnection();
    $query = "SELECT * FROM db_teste.tb_teste WHERE " . $nomeQuery . $sexoQuery . $cargoQuery;
    $stmt = $con->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOexception $error) {
    // echo $query . "<br>";
    die("Erro ao retornar os dados: " . $error->getMessage());
  }
    
  ?>

  <header class="d-flex align-items-center pb-2 my-2 border-bottom">
    <span class="fs-4">Tabela</span>
  </header>

  <div class="row my-4">
    <div class="col table-responsive">
      <!-- O id da table corresponde ao id setado no código do DataTables -->
      <table id="resultado" class="table table-sm table-bordered table-striped table-hover mt-4 mb-4">
        <thead>
          <tr>
            <th scope="col">Nome</th>
            <th scope="col">CPF</th>
            <th scope="col">Cargo</th>
            <th scope="col">Sexo</th>
            <th scope="col">Telefone</th>
          </tr>
        </thead>
        <tbody>
        <?php
          // Irá buscar os resultados do banco de dados através da variável $result
          foreach ($result as $row) {
            echo "<tr>";
            echo "<td>" . $row['nome'] . "</td>";
            echo "<td>" . $row['cpf'] . "</td>";
            echo "<td>" . $row['cargo'] . "</td>";
            echo "<td>" . $row['sexo'] . "</td>";
            echo "<td>" . $row['telefone'] . "</td>";
            echo "</tr>";
          }
        ?>
        </tbody>
      </table>
    </div>  
  </div>

  <?php } ?>

</div>

</main>
  <!-- JS JQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  
  <!-- JS Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

  <!-- JS DataTables -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/datatables.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#resultado').DataTable({
        // Ordenação da tabela pela coluna (0 = primeira coluna)
        order: [[0, 'asc']],
        // Quantidade de resultados a serem apresentados por página
        "iDisplayLength": 25,
        // Organização do layout da tabela
        dom: "<'row'<'col-sm-4'B><'col-sm-4 text-center'l><'col-sm-4'f>>" +
              "<'row'<'col-sm-12'tr>>" +
              "<'row'<'col-sm-6'i><'col-sm-6'p>>",
        buttons: [
          {
            // Botão para download da tabela em Excel
            extend: 'excelHtml5',
            orientation: 'landscape',
            className: 'btn btn-sm btn-success',
            messageTop: 'Resultado dos Dados da Consulta'
          },
          {
            // Botão para download da tabela em PDF
            extend: 'pdfHtml5',
            orientation: 'landscape',
            className: 'btn btn-sm btn-primary',
            messageTop: 'Resultado dos Dados da Consulta'
          }
        ]
      });
    });
  </script>
</body>
</html>