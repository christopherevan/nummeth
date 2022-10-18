<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Root Calculator (Bisection Method)</title>
    <link rel="icon" href="../wheliii-01.ico"></link>
    <style>
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 16px;
        }
        body{
            /* background: linear-gradient(90deg, rgba(97,186,255,1) 0%, rgba(166,239,253,1) 90.1%); */
            background: linear-gradient(to left top, #f2f8ff, #e2edfd, #d3e3fb, #c5d7f9, #b9ccf6);
        }
        a {
            text-decoration: none;
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            text-align: center;
            padding: 10px;
        }
        .button{
            font-weight: 600;
            color: whitesmoke;
            padding: 8px 15px 8px 15px;
            border-radius: 8px;
            border-style: none;
            background: #4560BE;
            /* box-shadow: 0 1px 3px -2px #9098A9; */
            cursor: pointer;
        }
        .boxinput{
            font-size: 1rem;
            font-weight: 600;
            width: 36%;
            color: #194569;
            padding: 5px 5px 5px 5px;
            border-radius: 5px;
            border-style: none;
            box-shadow: 0 1px 3px -2px #9098A9;
            font-size: 14px;
            text-align: center;
        }
        .container {
            /* margin: 0 auto; */
            margin-top: 90px;
            margin-bottom: 60px;
            padding: 30px;
            position: absolute;
            top: 72%;
            left: 50%;
            transform: translate(-50%, -50%);
            min-width: 1400px;
            min-height: 1500px;
            background: linear-gradient(to right top, #f2f8ff, #e7f1ff, #dceaff, #d3e3ff, #cbdbff);
        }
        label{
            width: 120px;
        }
</style>
<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
</head>
<body>
    <div class="container">
    <form action="./" method="post">
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
        
                $stopMethod = $_POST['kriteria'];
                $persamaan = $_POST['persamaan'];
                $ixl = $_POST['initXl'];
                $ixu = $_POST['initXu'];
                
                $disabled = "disabled";
                $py_persamaan = $persamaan;

                $input_persamaan = $persamaan;
                $input_xl = $ixl;
                $input_xu = $ixu;
            }
        ?>
        
        <h2 style="font-size: 24px;">Root Calculator (Bisection Method)</h2>
        <!-- HOW TO USE -->
        <table>
            <tr>
                <th colspan="2">Cara Penulisan Persamaan <a href="https://www.w3schools.com/python/module_math.asp" target="_blank">(?)</a></th>
            </tr>
            <tr>
                <td>Perkalian</td>
                <td>Gunakan <b>*</b>, contoh \(5x\) = 5*x</td>
            </tr>
            <tr>
                <td>Pangkat</td>
                <td>Gunakan <b>**</b>, contoh \(x^3+x^2\) = x**3+x**2</td>
            </tr>
            <tr>
                <td>Fungsi</td>
                <td>Gunakan <b>math.*()</b>, contoh \(\sqrt{(...)}\) = math.sqrt(...)</td>
            </tr>
            <tr>
                <td>Konstanta</td>
                <td>Gunakan <b>math.*</b>, contoh \(e\) = math.e</td>
            </tr>
        </table>
        <!-- INPUT -->
        <div>
            <div style="width: 50%; display: inline-block;">
            <p><label>Persamaan: </label><input type="text" class="boxinput" name="persamaan" <?php echo "value='$input_persamaan'"; ?> required autofocus></p>
            <p><label>Initial \(x_l\) = </label><input type="number" class="boxinput" name="initXl" step="any" <?php echo "value=$input_xl"; ?> required></p>
            <p><label>Initial \(x_u\) = </label><input type="number" class="boxinput" name="initXu"  step="any" <?php echo "value=$input_xu"; ?> required></p>
            
            <p>Kriteria Berhenti</p>
            <div style="padding-left: 10px;">
                <p><input type="radio" name="kriteria" id="ds" value="1" class="radiobtn" checked><label for="ds">Digit Signifikan \(\large{|\epsilon _a| \lt \epsilon _s}\)</label></p>
                <div id="kriteria-berhenti-ds">
                </div>
                <p><input type="radio" name="kriteria" id="mi" value="2" class="radiobtn"><label for="mi">Maksimum Iterasi</label></p>
                <div id="kriteria-berhenti-maxiter">
                </div>
                <p><input type="radio" name="kriteria" id="fxr" value="3" class="radiobtn"><label for="fxr">\(|f(x_r)| \lt \varepsilon\)</label></p>
                <div id="kriteria-berhenti-fxr">
                </div>
            </div>
            <input type="submit" class="button" name="submit" value="Calculate"><br><br>
            </div>
        </div>
    </form>

    <?php
    if (isset($_POST['submit'])) {
        
        $output = shell_exec("python hitung.py $persamaan $as $ixl $ixu $stopMethod $maxIter $epsilon 2>&1");

         // if dibawah ngecek fxl*fxu >= 0
        if (!str_contains($output, "<tr>")) {
            echo($output);
        } else {
            echo "<table>";
            echo "<tr><th>Iterasi</th><th>\(x_l\)</th><th>\(x_u\)</th><th>\(x_r\)</th><th>\(f(x_l)\)</th>
                <th>\(f(x_u)\)</th><th>\(f(x_r)\)</th><th>\(|\\epsilon _a|\%\)</th></tr>";
            echo($output);
        }
    }
    ?>
    </div>
</body>
<script src="./jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function(){
        if ($('#ds').is(':checked')) {
            var input = "<p style='margin-left:22px;'><span>Angka Signifikan:&nbsp;&nbsp;</span><input type='number' class='boxinput' name='angka_signifikan' min=1 required></p>"

            $('#kriteria-berhenti-fxr').children().remove();
            $('#kriteria-berhenti-maxiter').children().remove();
            $('#kriteria-berhenti-ds').children().remove();

            $('#kriteria-berhenti-ds').append(input);
        } else if ($('#mi').is(':checked')) {
            var input = "<p style='margin-left:22px;'><span>Maksimum Iterasi:&nbsp;&nbsp;</span><input type='number' class='boxinput' name='stopMaxIter' min=1 required></p>"

            $('#kriteria-berhenti-fxr').children().remove();
            $('#kriteria-berhenti-maxiter').children().remove();
            $('#kriteria-berhenti-ds').children().remove();

            $('#kriteria-berhenti-maxiter').append(input);
        } else {
            var input = "<p style='margin-left:22px;'><span>Epsilon:&nbsp;&nbsp;</span><input type='number' class='boxinput' style='margin-left:5px' name='stopFxr' min=0 max=1 step=any required></p>"

            $('#kriteria-berhenti-fxr').children().remove();
            $('#kriteria-berhenti-maxiter').children().remove();
            $('#kriteria-berhenti-ds').children().remove();

            $('#kriteria-berhenti-fxr').append(input);
        }
    })

    $('body').on('click', '#mi', function(){
        var input = "<p style='margin-left:22px;'><span>Maksimum Iterasi:&nbsp;&nbsp;</span><input type='number' class='boxinput' style='margin-left:5px' name='stopMaxIter' min=1 required></p>"

        $('#kriteria-berhenti-fxr').children().remove();
        $('#kriteria-berhenti-maxiter').children().remove();
        $('#kriteria-berhenti-ds').children().remove();

        $('#kriteria-berhenti-maxiter').append(input);
    })

    $('body').on('click', '#ds', function(){
        var input = "<p style='margin-left:22px;'><span>Angka Signifikan:&nbsp;&nbsp;</span><input type='number' class='boxinput' name='angka_signifikan' min=1 required></p>"

        $('#kriteria-berhenti-fxr').children().remove();
        $('#kriteria-berhenti-maxiter').children().remove();
        $('#kriteria-berhenti-ds').children().remove();

        $('#kriteria-berhenti-ds').append(input);
    })

    $('body').on('click', '#fxr', function(){
        var input = "<p style='margin-left:22px;'><span>Epsilon:&nbsp;&nbsp;</span><input type='number' class='boxinput' name='stopFxr' min=0 max=1 step=any required></p>"

        $('#kriteria-berhenti-fxr').children().remove();
        $('#kriteria-berhenti-maxiter').children().remove();
        $('#kriteria-berhenti-ds').children().remove();

        $('#kriteria-berhenti-fxr').append(input);
    })
</script>
</html>
