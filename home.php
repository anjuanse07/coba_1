<!DOCTYPE HTML>
<html>
  <head>
    <title>ECG Monitoring</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="icon" href="data:,">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
    <style>
      html {font-family: Arial; display: inline-block; text-align: center;}
      p {font-size: 1.2rem;}
      h4 {font-size: 0.8rem;}
      body {margin: 0;}
      .topnav {overflow: hidden; background-color: #0c6980; color: white; font-size: 1.2rem; padding: 15px;}
      .content {padding: 5px; }
      .card {background-color: white; box-shadow: 0px 0px 10px 1px rgba(140,140,140,.5); border: 1px solid #0c6980; border-radius: 15px;}
      .card.header {background-color: #0c6980; color: white; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; border-top-right-radius: 12px; border-top-left-radius: 12px; padding-top: 10px;}
      .cards {max-width: 700px; margin: 0 auto; display: grid; grid-gap: 2rem; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));}
      .reading {font-size: 1.3rem;}
      .packet {color: #bebebe;}
      .intervalColor {color: #fd7e14;}
      .classifyColor {color: #1b78e2;}
      .statusreadColor {color: #702963; font-size:12px;}
      .LEDColor {color: #183153;}
      
      /* ----------------------------------- Toggle Switch */
      .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
      }
      .jumbotron {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 20px;
        justify-content: center;
        display: flex;
      }

      canvas {
        max-width: auto;
        justify-content: center;
        column-fill: auto;
      }

      .switch input {display:none;}

      .sliderTS {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #D3D3D3;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 34px;
      }

      .sliderTS:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: #f7f7f7;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 50%;
      }

      input:checked + .sliderTS {
        background-color: #00878F;
      }

      input:focus + .sliderTS {
        box-shadow: 0 0 1px #2196F3;
      }

      input:checked + .sliderTS:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
      }

      .sliderTS:after {
        content:'OFF';
        color: white;
        display: block;
        position: absolute;
        transform: translate(-50%,-50%);
        top: 50%;
        left: 70%;
        font-size: 10px;
        font-family: Verdana, sans-serif;
      }

      input:checked + .sliderTS:after {  
        left: 25%;
        content:'ON';
      }

      input:disabled + .sliderTS {  
        opacity: 0.3;
        cursor: not-allowed;
        pointer-events: none;
      }

      footer {
        margin-top: 30px;
        padding: 20px;
        color: white;
        background-color: #0c6980;
        text-align: center;
        font-weight: bold;
      }
      /* ----------------------------------- */
    </style>
  </head>
  
  <body>
    <div class="topnav">
      <h3>ECG SIGNAL MONITORING</h3>
    </div>
    <br>

    <div class="jumbotron">
      <canvas
        class="p-2 mx-1 shadow-lg rounded-3 d-flex justify-content-center no-chart"
        id="myChart"
        width="1200"
        height="500"
      ></canvas>
    </div>
    <br>

    <!-- __ DISPLAYS MONITORING AND CONTROLLING ____________________________________________________________________________________________ -->
    <div class="content">
      <div class="cards">
        
        <!-- == MONITORING ======================================================================================== -->
        <div class="card">
          <div class="card header">
            <h3 style="font-size: 1rem;">Segments and Parameters</h3>
          </div>
          
          <!-- Displays the humidity and temperature values received from ESP32. *** -->
          <h4 style ="padding-top:15px" class="intervalColor"><i class="fas fa-heart"></i> RR AVG Interval</h4>
          <p class="intervalColor"><span class="reading"><span id="rr"></span></span></p>

          <h4 class="intervalColor"><i class="fas fa-heart"></i> PR AVG Interval</h4>
          <p class="intervalColor"><span class="reading"><span id="pr"></span></span></p>

          <h4 class="intervalColor"><i class="fas fa-heart"></i> QS AVG Interval </h4>
          <p class="intervalColor"><span class="reading"><span id="qs"></span></span></p>

          <h4 class="intervalColor"><i class="fas fa-heart"></i> QT AVG Interval </h4>
          <p class="intervalColor"><span class="reading"><span id="qt"></span></span></p>

          <h4 class="intervalColor"><i class="fas fa-heart"></i> ST AVG Interval </h4>
          <p class="intervalColor"><span class="reading"><span id="st"></span></span></p>

          <h4 class="classifyColor"><i class="fas fa-heart"></i> AVG Heart Rate</h4>
          <p class="classifyColor"><span class="reading"><span id="heartrate"></span> bpm</span></p>

          <h4 class="classifyColor"><i class="fas fa-heart"></i> Classification</h4>
          <p class="classifyColor"><span class="reading"> Result : <span id="classification"></span></span></p>
          <!-- *********************************************************************** -->
        </div>
               
      </div>
    </div>
    
    <br>
    
    <div class="content">
      <div class="cards">
        <div class="card header" style="border-radius: 15px;">
            <h3 style="padding-top: 5px; font-size: 0.7rem;">LAST TIME RECEIVED DATA FROM DEVICE [ <span id="ESP32_01_LTRD"></span> ]</h3>
            <button class='btn btn-outline-light btn-floating m-1' onclick="window.location.href='recordtable.php';">Open Record Table</button>
            <h3 style="font-size: 0.7rem;"></h3>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="cards">
        <div class="card header" style="border-radius: 15px;">
            <button class='btn btn-outline-light btn-floating m-1' onclick="window.location.href='history.php';">Open Graph History</button>
            <h3 style="font-size: 0.7rem;"></h3>
        </div>
      </div>
    </div>
    <!-- ___________________________________________________________________________________________________________________________________ -->
    
    <footer class="contens" id="footer">
      <div class="cards p-4 pb-0">
        <section class="mb-4">
          <a
            class="btn btn-outline-light btn-floating m-1"
            href="https://mail.google.com/mail/u/0/?view=cm&tf=1&fs=1&to=emailanda@gmail.com!"
            role="button"
            ><i class="bi bi-envelope"></i
          ></a>
          <a
            class="btn btn-outline-light btn-floating m-1"
            href="https://www.linkedin.com/in/seanjuliuslase/"
            role="button"
            ><i class="bi bi-linkedin"></i
          ></a>
          <a
            class="btn btn-outline-light btn-floating m-1"
            href="https://github.com/anjuanse07"
            role="button"
            ><i class="bi bi-github"></i
          ></a>
          <a
            class="btn btn-outline-light btn-floating m-1"
            href="https://www.instagram.com/seanjuu_/"
            role="button"
            ><i class="bi bi-instagram"></i
          ></a>
        </section>
      </div>

      <div class="text-center p-3">
        2023 &#169;: Monitoring EKG menggunakan proktol MQTT
      </div>
      <!-- Copyright -->
    </footer>
    <?php 
      include 'database.php';
      $pdo = Database::connect();
      $max_ecg_id_query = 'SELECT MAX(ecg_id) AS max_ecg_id FROM ecg_raw_test_2';
      $max_ecg_id_result = $pdo->query($max_ecg_id_query);
      $max_ecg_id = $max_ecg_id_result->fetch(PDO::FETCH_ASSOC)['max_ecg_id'];

      $sql = 'SELECT data_val FROM ecg_raw_test_2 WHERE ecg_id = :ecg_id';
      $q = $pdo->prepare($sql);
      $q->execute(array(':ecg_id' => $max_ecg_id));

      $graphData = array();
      while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
          $graphData[] = $row['data_val'];
      }
      $labels = range(1, count($graphData));

      Database::disconnect();

      $graphData = json_encode($graphData);
      $labels = json_encode($labels);
    ?>
    <script>
      const labels = <?php echo $labels; ?>;
      const graphData = {
          labels: labels,
          datasets: [
              {
              label: "ECG Live Graph",
              backgroundColor: "rgb(255, 0, 0)",
              borderColor: "rgb(255, 0, 0)",
              data: <?php echo $graphData; ?>,
              pointRadius: 0,
              fill: false,
              },
          ],
          options: {
              animation: {
              onComplete: function () {
                  console.log(myChart.toBase64Image());
              },
              },
          },
      };

      const config = {
          type: "line",
          data: graphData,
          options: {
              scales: {
              y: {
                  title: {
                  display: true,
                  text: "Amplitudo (V)",
                  },
              },
              x: {
                  ticks: {
                  autoskip: false,
                  },
                  title: {
                  display: true,
                  text: "n_data",
                  },
              },
              },
              spanGaps: true,
              responsive: true,
              maintainAspectRatio: true,
          },
      };

      const myChart = new Chart(document.getElementById('myChart'), config);
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/2.0.12/typed.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0"></script> -->
    <!-- <script src="main_2.js" type="module"></script> -->
    
    <script>
        
      //------------------------------------------------------------
      document.getElementById("rr").innerHTML = "0"; 
      document.getElementById("pr").innerHTML = "0";
      document.getElementById("qs").innerHTML = "0"; 
      document.getElementById("qt").innerHTML = "0"; 
      document.getElementById("st").innerHTML = "0"; 
      document.getElementById("heartrate").innerHTML = "0"; 
      document.getElementById("classification").innerHTML = "unknown"; 
      document.getElementById("ESP32_01_LTRD").innerHTML = "NN";
      //------------------------------------------------------------
      
      Get_Data("1");
      
      setInterval(myTimer, 1000);
      
      //------------------------------------------------------------
      function myTimer() {
        Get_Data("1");
      }
      //------------------------------------------------------------
      
      //------------------------------------------------------------
      function Get_Data(id) {
				if (window.XMLHttpRequest) {
          xmlhttp = new XMLHttpRequest();
        } else {
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            const myObj = JSON.parse(this.responseText);
            if (myObj.id == "1") {
              document.getElementById("rr").innerHTML = myObj.rr;
              document.getElementById("pr").innerHTML = myObj.pr;
              document.getElementById("qs").innerHTML = myObj.qs; 
              document.getElementById("qt").innerHTML = myObj.qt; 
              document.getElementById("st").innerHTML = myObj.st; 
              document.getElementById("heartrate").innerHTML = myObj.heartrate; 
              document.getElementById("classification").innerHTML = myObj.classification; 
              document.getElementById("ESP32_01_LTRD").innerHTML = "Time : " + myObj.is_time + " | Date : " + myObj.is_date;
              
            }
          }
        };
        xmlhttp.open("POST","getdata.php",true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("id="+id);
			}
      
    </script>
  </body>
</html>