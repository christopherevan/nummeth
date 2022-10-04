<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kalkulatorz</title>
    <style>
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body{
            background: linear-gradient(90deg, rgba(97,186,255,1) 0%, rgba(166,239,253,1) 90.1%);
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            text-align: center;
            padding: 10px;
        }
</style>
</head>
<body>
    <form action="index.php" method="post" autocomplete="off">
        <?php
            $input_persamaan = "";
            $input_xl = "";
            $input_xu = "";
            $disabled = "";
            $maxIter = 0;
            $epsilon = 0;
            $as = 0;
            

            if (isset($_POST['submit'])) {

                if (isset($_POST['angka_signifikan'])) {
                    $as = $_POST['angka_signifikan'];
                }

                if (isset($_POST['stopMaxIter'])) {
                    $maxIter = $_POST['stopMaxIter'];
                }
        
                if (isset($_POST['stopFxr'])) {
                    $epsilon = $_POST['stopFxr'];
                }
        
                // print_r($_POST);
                $stopMethod = $_POST['kriteria'];
                // $as = $_POST['angka_signifikan'];
                $persamaan = $_POST['persamaan'];
                $ixl = $_POST['initXl'];
                $ixu = $_POST['initXu'];
                
                $disabled = "disabled";
                $py_persamaan = $persamaan;

                // if (str_contains($persamaan, "e")) {
                //     $py_persamaan = str_replace("e", )
                // }

                $input_persamaan = $persamaan;
                $input_xl = $ixl;
                $input_xu = $ixu;

                // echo "inputxlxu $ixl $ixu";
            }
        ?>
        
        <h2>Root Calculator (Bisection Method)</h2>
        <p><label>Persamaan </label><input type="text" name="persamaan" id="" <?php echo "value='$input_persamaan'"; ?> required autocomplete="off" autofocus></p>
        <p><label>Initial xl </label><input type="number" name="initXl" id="" <?php echo "value=$input_xl"; ?> required autocomplete="off"></p>
        <p><label>Initial xu </label><input type="number" name="initXu" id="" <?php echo "value=$input_xu"; ?> required autocomplete="off"></p>
        <p>Kriteria Berhenti</p>

        <div style="padding-left: 10px;">
        
            <p><input type="radio" name="kriteria" id="ds" value="1" class="radiobtn" checked><label for="ds">Digit Signifikan |ea| < es</label></p>
            <div id="kriteria-berhenti-ds">
            </div>
            <p><input type="radio" name="kriteria" id="mi" value="2" class="radiobtn"><label for="mi">Maksimum Iterasi</label></p>
            <div id="kriteria-berhenti-maxiter">
            </div>
            <p><input type="radio" name="kriteria" id="fxr" value="3" class="radiobtn"><label for="fxr">|f(xr)| < e</label></p>
            <div id="kriteria-berhenti-fxr">
            </div>
        </div>
        <input type="submit" name="submit" value="Calculate"><br><br>
    </form>

    <?php
    if (isset($_POST['submit'])) {
        

        
        // biar ga error2, pakek pythonnya terakhir2 aja biar gampang styling halamannya
        // Body Tabel
        $output = shell_exec("python hitung.py '$persamaan' $as $ixl $ixu $stopMethod $maxIter $epsilon");

        // yg dibawah ini output dari pythonnya
        // $output = "<tr><td>1</td><td>1</td><td>2</td><td>1.5</td><td>+</td><td>-</td><td>+</td><td>-</td></tr>
        //             <tr><td>1</td><td>1</td><td>2</td><td>1.5</td><td>+</td><td>-</td><td>+</td><td>1.23456789</td></tr>
        //             <tr><td>1</td><td>1</td><td>2</td><td>1.5</td><td>+</td><td>-</td><td>+</td><td>1.23456789</td></tr>
        //             <tr><td>1</td><td>1</td><td>2</td><td>1.5</td><td>+</td><td>-</td><td>+</td><td>1.23456789</td></tr>
        //             <tr><td>1</td><td>1</td><td>2</td><td>1.5</td><td>+</td><td>-</td><td>+</td><td>1.23456789</td></tr>
        //             <tr><td>1</td><td>1</td><td>2</td><td>1.5</td><td>+</td><td>-</td><td>+</td><td>1.23456789</td></tr>
        //             <tr><td>1</td><td>1</td><td>2</td><td>1.5</td><td>+</td><td>-</td><td>+</td><td>1.23456789</td></tr>
        //             <tr><td>1</td><td>1</td><td>2</td><td>1.5</td><td>+</td><td>-</td><td>+</td><td>1.23456789</td></tr>
        //             </table>
        //             <p>Salah satu akar dari {} adalah 1.5939488</p>";

        // if dibawah ngecek fxl*fxu >= 0
        if (!str_contains($output, "<tr>")) {
            die($output);
        } else {
            // echo "<p>Angka Signifikan: $as</p>";
            echo "<p>Persamaan: $persamaan</p>";
            echo "<table>";
            echo "<tr><th>Iterasi</th><th>xl</th><th>xu</th><th>xr</th><th>f(xl)</th><th>f(xu)</th><th>f(xr)</th><th>|ea|%</th></tr>";
            echo($output);
        }
    }
    ?>
</body>
<script src="./jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function(){
        if ($('#ds').is(':checked')) {
            var input = "<p><span>Angka Signifikan:&nbsp;&nbsp;</span><input type='number' name='angka_signifikan' min=1 required></p>"

            $('#kriteria-berhenti-fxr').children().remove();
            $('#kriteria-berhenti-maxiter').children().remove();
            $('#kriteria-berhenti-ds').children().remove();

            $('#kriteria-berhenti-ds').append(input);
        } else if ($('#mi').is(':checked')) {
            var input = "<p><span>Maximum Iteration:&nbsp;&nbsp;</span><input type='number' name='stopMaxIter' min=1 required></p>"

            $('#kriteria-berhenti-fxr').children().remove();
            $('#kriteria-berhenti-maxiter').children().remove();
            $('#kriteria-berhenti-ds').children().remove();

            $('#kriteria-berhenti-maxiter').append(input);
        } else {
            var input = "<p><span>Epsilon:&nbsp;&nbsp;</span><input type='number' name='stopFxr' min=0 max=1 step=any required></p>"

            $('#kriteria-berhenti-fxr').children().remove();
            $('#kriteria-berhenti-maxiter').children().remove();
            $('#kriteria-berhenti-ds').children().remove();

            $('#kriteria-berhenti-fxr').append(input);
        }
    })

    $('body').on('click', '#mi', function(){
        var input = "<p><span>Maximum Iteration:&nbsp;&nbsp;</span><input type='number' name='stopMaxIter' min=1 required></p>"

        $('#kriteria-berhenti-fxr').children().remove();
        $('#kriteria-berhenti-maxiter').children().remove();
        $('#kriteria-berhenti-ds').children().remove();

        $('#kriteria-berhenti-maxiter').append(input);
    })

    $('body').on('click', '#ds', function(){
        var input = "<p><span>Angka Signifikan:&nbsp;&nbsp;</span><input type='number' name='angka_signifikan' min=1 required></p>"

        $('#kriteria-berhenti-fxr').children().remove();
        $('#kriteria-berhenti-maxiter').children().remove();
        $('#kriteria-berhenti-ds').children().remove();

        $('#kriteria-berhenti-ds').append(input);
    })

    $('body').on('click', '#fxr', function(){
        var input = "<p><span>Epsilon:&nbsp;&nbsp;</span><input type='number' name='stopFxr' min=0 max=1 step=any required></p>"

        $('#kriteria-berhenti-fxr').children().remove();
        $('#kriteria-berhenti-maxiter').children().remove();
        $('#kriteria-berhenti-ds').children().remove();

        $('#kriteria-berhenti-fxr').append(input);
    })
</script>
</html>
