<?php
/**
 * Plugin Name: Astro-Kids Hub Official
 * Description: Portale Giochi NASA Brindisi. Versione 5.4 - Hub di Costruzione Officina Stellare.
 * Version:     5.4
 * Author:      Nuova Associazione Studi Astronomici
 */

if (!defined('ABSPATH')) exit;

add_shortcode('astro_kids_hub', function() {
    ob_start();
    ?>
    <style>
        /* --- STILI ORIGINALI 3.1 (RIPRISTINATI E BLOCCATI) --- */
        .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {color:#fff}
        :root { --space-dark: #050a18; --nasa-blue: #1a237e; --nasa-yellow: #F5AD27; }
        #astro-hub-main { font-family: 'Segoe UI', sans-serif; color: #fff; text-align: center; background:#000; padding-bottom:40px; min-height: 800px; }
        
        .hub-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; padding: 20px; }
        .game-card {
            background: var(--nasa-blue); border-radius: 25px; padding: 30px; cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); border: 3px solid transparent;
        }
        .game-card:hover { transform: translateY(-12px); border-color: var(--nasa-yellow); }
        .card-art { height: 100px; display: flex; justify-content: center; align-items: center; margin-bottom: 15px; font-size: 60px; }
        .ufo-anim { animation: floatUfo 3s ease-in-out infinite; }
        @keyframes floatUfo { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }

        #game-stage { display: none; background: #000; padding: 20px; position: relative; overflow: hidden; min-height: 600px; }
        .back-btn { background: #ff5252; color: #fff; border: none; padding: 10px 25px; border-radius: 50px; cursor: pointer; margin-bottom: 20px; font-weight: bold; z-index: 100; position: relative; }

        .blink-success { animation: bgGreen 0.6s; } @keyframes bgGreen { 50% { background: #2e7d32; } }
        .blink-error { animation: bgRed 0.6s; } @keyframes bgRed { 50% { background: #c62828; } }

        /* --- STILI GIOCHI --- */
        #space-viewport { position: relative; height: 400px; background: radial-gradient(circle, #1b2735 0%, #090a0f 100%); border-radius: 15px; overflow: hidden; border: 2px solid #333; }
        #starfield { position: absolute; width: 200%; height: 100%; background: url('https://www.transparenttextures.com/patterns/stardust.png'); animation: slideStars 30s linear infinite; opacity: 0.6; }
        @keyframes slideStars { from { transform: translateX(0); } to { transform: translateX(-50%); } }
        #planet-display { position: absolute; top: 45%; left: 50%; transform: translate(-50%, -50%); font-size: 130px; transition: all 1s ease; filter: drop-shadow(0 0 20px rgba(255,255,255,0.2)); }
        #question-box { max-width:850px; position: absolute; top: 20px; left: 50%; transform: translateX(-50%); background: rgba(5, 10, 24, 0.95); border: 2px solid var(--nasa-yellow); padding: 20px; border-radius: 15px; width: 85%; z-index: 10; text-align: center; }
        .btn-game { background: var(--nasa-yellow); color: #050a18; border: none; padding: 10px 20px; margin: 8px; border-radius: 50px; cursor: pointer; font-weight: bold; transition: 0.3s; }
        #mission-log { margin-top: 20px; padding: 20px; background: #0a122a; border-radius: 12px; border-left: 6px solid var(--nasa-yellow); text-align: left; }
        #passport-wrapper { background: #fff; color: #1a237e; padding: 30px; border: 8px double #1a237e; text-align: center; display: none; }

        .weight-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 12px; margin-top: 20px; }
        .planet-card { background: var(--nasa-blue); padding: 12px; border-radius: 15px; border: 2px solid transparent; cursor: pointer; transition: 0.3s; font-size: 0.9em; }
        .planet-card:hover { border-color: var(--nasa-yellow); transform: scale(1.05); }

        #puzzle-canvas { background: #050a18; border-radius: 15px; border: 2px solid #333; margin: 20px auto; display: block; }
        .puzzle-choice-container { display: flex; justify-content: center; gap: 15px; margin-top: 20px; flex-wrap: wrap; }

        /* --- STILI OFFICINA --- */
        .workshop-card { background: rgba(26, 35, 126, 0.5); border-radius: 20px; padding: 20px; border: 1px solid var(--nasa-yellow); flex: 1; min-width: 250px; }
        .workshop-container { display: flex; gap: 20px; flex-wrap: wrap; justify-content: center; margin-top: 30px; }
    </style>

    <div id="astro-hub-main">
        <div id="hub-home">
            <h1 style="color:var(--nasa-yellow); padding-top:20px;">Astro-Kids Hub 🚀</h1>
            <div class="hub-grid">
                <div class="game-card" onclick="loadExplorer()">
                    <div class="card-art ufo-anim">🚀</div>
                    <h3>Esploratore Spaziale</h3>
                    <p>Mappa i pianeti e ottieni il Passaporto!</p>
                </div>
                <div class="game-card" onclick="loadWeight()">
                    <div class="card-art ufo-anim">⚖️</div>
                    <h3>Peso Spaziale</h3>
                    <p>Scopri quanto pesi sugli altri mondi!</p>
                </div>
                <div class="game-card" onclick="loadPuzzle()">
                    <div class="card-art ufo-anim">✨</div>
                    <h3>Mistero Stellare</h3>
                    <p>Indovina le costellazioni del cielo!</p>
                </div>
                <div class="game-card" onclick="loadWorkshop()">
                    <div class="card-art ufo-anim">✂️</div>
                    <h3>Officina Stellare</h3>
                    <p>Costruisci i tuoi strumenti scientifici!</p>
                </div>
            </div>
        </div>

        <div id="game-stage">
            <button class="back-btn" onclick="showHub()">⬅ TORNA ALL'HUB</button>
            <div id="game-renderer"></div>
        </div>
    </div>

    <script>
        function showHub() { 
            document.getElementById('hub-home').style.display = 'block'; 
            document.getElementById('game-stage').style.display = 'none'; 
            document.getElementById('game-renderer').innerHTML = '';
        }

        /* --- 1. ESPLORATORE (FIX PASSAPORTO) --- */
        let currentStep = 0;
        const planetsData = [
            { emoji: "🌑", q: "Pianeta più piccolo e vicino al Sole. Chi sono?", a: ["Marte", "Mercurio", "Luna"], correct: 1, info: "Ottimo! Hai identificato Mercurio!" },
            { emoji: "🌕", q: "Pianeta più luminoso e caldo nel cielo. Chi sono?", a: ["Venere", "Giove", "Sirio"], correct: 0, info: "Centro! È Venere!" },
            { emoji: "🌍", q: "L'unico pianeta con acqua liquida e vita. Chi sono?", a: ["Marte", "Terra", "Urano"], correct: 1, info: "Bentornato sulla Terra!" },
            { emoji: "🔴", q: "Mi chiamano il Pianeta Rosso. Chi sono?", a: ["Venere", "Marte", "Saturno"], correct: 1, info: "Bravo! Marte!" },
            { emoji: "🟠", q: "Gigante gassoso con la Macchia Rossa. Chi sono?", a: ["Giove", "Urano", "Saturno"], correct: 0, info: "Giove!" },
            { emoji: "🪐", q: "Famoso per i miei spettacolari anelli. Chi sono?", a: ["Nettuno", "Giove", "Saturno"], correct: 2, info: "È Saturno!" },
            { emoji: "💎", q: "Gigante di ghiaccio azzurro sdraiato. Chi sono?", a: ["Urano", "Nettuno", "Plutone"], correct: 0, info: "Ottimo! È Urano!" },
            { emoji: "🔵", q: "L'ultimo pianeta, blu e ventosissimo. Chi sono?", a: ["Urano", "Marte", "Nettuno"], correct: 2, info: "Missione compiuta! È Nettuno!" }
        ];

        function loadExplorer() {
            document.getElementById('hub-home').style.display = 'none';
            document.getElementById('game-stage').style.display = 'block';
            currentStep = 0; renderExplorer();
        }

        function renderExplorer() {
            const renderer = document.getElementById('game-renderer');
            renderer.innerHTML = `<div id="alien-game-ui"><div id="space-viewport"><div id="starfield"></div><div id="planet-display">${planetsData[currentStep].emoji}</div><div id="question-box"><p style="font-size:1.2em">${planetsData[currentStep].q}</p><div id="ans-btns"></div></div></div><div id="mission-log"><p id="log-txt">In attesa...</p></div><div id="passport-wrapper"></div></div>`;
            planetsData[currentStep].a.forEach((ans, i) => {
                const b = document.createElement('button'); b.className = 'btn-game'; b.innerText = ans;
                b.onclick = () => {
                    const log = document.getElementById('mission-log');
                    if(i === planetsData[currentStep].correct) {
                        document.getElementById('log-txt').innerText = planetsData[currentStep].info;
                        log.classList.add('blink-success'); setTimeout(() => log.classList.remove('blink-success'), 600);
                        if(currentStep < planetsData.length - 1) { 
                            currentStep++; 
                            setTimeout(renderExplorer, 1500); 
                        } else {
                            setTimeout(showFinalExplorer, 1500); // Passaggio finale
                        }
                    } else { log.classList.add('blink-error'); setTimeout(() => log.classList.remove('blink-error'), 600); }
                }; document.getElementById('ans-btns').appendChild(b);
            });
        }

        function showFinalExplorer() {
            const renderer = document.getElementById('game-renderer');
            renderer.innerHTML = `<div id="alien-game-ui"><div id="space-viewport"><div id="question-box"><h2>🏆 MISSIONE COMPIUTA!</h2><p>Hai mappato con successo il Sistema Solare.<br>Inserisci il tuo nome per ricevere il Passaporto Galattico:</p><input type="text" id="p-name" placeholder="Tuo Nome" style="padding:10px; border-radius:5px; border:none; width:250px; text-align:center; font-size:1.1em; color:#000;"><br><br><button class="btn-game" onclick="genPass()">GENERA PASSAPORTO</button></div></div><div id="passport-wrapper"></div></div>`;
        }

        function genPass() {
            const name = document.getElementById('p-name').value || "Esploratore Spaziale";
            document.getElementById('space-viewport').style.display = 'none';
            const pass = document.getElementById('passport-wrapper');
            pass.style.display = 'block';
            pass.innerHTML = `
                <p style="margin-top:0; color:#000;"><strong>Nuova Associazione Studi Astronomici</strong></p>
                <div style="padding:20px; color:#1a237e; border: 4px solid #1a237e; position:relative; background:#fff;">
                    <h1 style="margin:0; font-size: 28px; color:#1a237e;">PASSAPORTO GALATTICO</h1>
                    <hr style="border: 1px solid #1a237e;">
                    <p style="margin:10px 0;">Si certifica che il pilota:</p>
                    <h2 style="color: #d32f2f; margin: 15px 0; font-size: 32px;">${name}</h2>
                    <p style="margin:10px 0;">Ha esplorato con successo tutti i pianeti.</p>
                    <div style="width: 100px; height: 100px; border: 3px dashed #1a237e; border-radius: 50%; margin: 20px auto; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; transform: rotate(-15deg);">NASA BRINDISI</div>
                </div>
                <br>
                <button class="btn-game" onclick="window.print()">🖨️ STAMPA IL TUO TITOLO</button>
            `;
        }

        /* --- 2. BILANCIA (RIPRISTINATO 3.1) --- */
        const fullWeights = [
            { name: "Sole", g: 27.9, emoji: "☀️", fact: "10 automobili!" },
            { name: "Mercurio", g: 0.38, emoji: "🌑", fact: "Leggero!" },
            { name: "Venere", g: 0.91, emoji: "🌕", fact: "Quasi come sulla Terra." },
            { name: "Luna", g: 0.16, emoji: "🌑", fact: "Salti alti!" },
            { name: "Marte", g: 0.38, emoji: "🔴", fact: "Agile come un ninja!" },
            { name: "Giove", g: 2.34, emoji: "🟠", fact: "Pesante come un elefante!" },
            { name: "Saturno", g: 1.06, emoji: "🪐", fact: "Un pizzico più pesante." },
            { name: "Urano", g: 0.92, emoji: "💎", fact: "Leggermente più leggero." },
            { name: "Nettuno", g: 1.14, emoji: "🔵", fact: "Cammineresti a fatica." },
            { name: "Plutone", g: 0.06, emoji: "❄️", fact: "Voleresti via!" }
        ];

        function loadWeight() {
            document.getElementById('hub-home').style.display = 'none';
            document.getElementById('game-stage').style.display = 'block';
            const renderer = document.getElementById('game-renderer');
            renderer.innerHTML = `<div id="weight-game-ui"><h2>⚖️ BILANCIA INTERPLANETARIA</h2><input type="number" id="user-w" value="40" style="text-align:center;color:#000;"><div class="weight-grid" id="w-grid"></div><div id="w-res" style="display:none;margin-top:20px;"><div id="final-w" style="font-size:2.5em;color:var(--nasa-yellow)"></div><p id="weight-fact"></p></div></div>`;
            fullWeights.forEach(w => {
                const d = document.createElement('div'); d.className = 'planet-card'; d.innerHTML = `${w.emoji}<br>${w.name}`;
                d.onclick = () => {
                    const val = parseFloat(document.getElementById('user-w').value) || 0;
                    document.getElementById('w-res').style.display = 'block';
                    document.getElementById('final-w').innerText = (val * w.g).toFixed(1) + " kg";
                    document.getElementById('weight-fact').innerText = w.fact;
                }; renderer.querySelector('#w-grid').appendChild(d);
            });
        }

        /* --- 3. MISTERO STELLARE (RIPRISTINATO 3.1) --- */
        const constellationData = [
            { name: "Cigno", lines: [[150,40,150,220], [60,100,240,100]], stars: [[150,40],[150,100],[150,220],[60,100],[240,100]] },
            { name: "Cassiopea", lines: [[40,60,80,120], [80,120,130,70], [130,70,170,130], [170,130,220,60]], stars: [[40,60],[80,120],[130,70],[170,130],[220,60]] },
            { name: "Orione", lines: [[80,50,220,50], [80,50,130,120], [220,50,170,120], [130,120,170,120], [130,120,80,200], [170,120,220,200], [80,200,220,200]], stars: [[80,50],[220,50],[130,120],[150,120],[170,120],[80,200],[220,200]] },
            { name: "Ercole", lines: [[120,80,180,80], [180,80,200,150], [200,150,100,150], [100,150,120,80], [120,80,80,40], [180,80,220,40], [100,150,70,190], [200,150,230,190]], stars: [[120,80], [180,80], [200,150], [100,150], [80,40], [220,40], [70,190], [230,190]] },
            { name: "Pegaso", lines: [[100,70,200,70], [200,70,200,170], [200,170,100,170], [100,170,100,70]], stars: [[100,70], [200,70], [200,170], [100,170]] },
            { name: "Perseo", lines: [[150,50,150,120], [150,120,110,180], [150,120,190,180], [110,180,80,200]], stars: [[150,50], [150,120], [110,180], [190,180], [80,200]] },
            { name: "Freccia", lines: [[80,120,180,120], [180,120,210,100], [180,120,210,140]], stars: [[80,120], [130,120], [180,120], [210,100], [210,140]] }
        ];

        function loadPuzzle() { document.getElementById('hub-home').style.display = 'none'; document.getElementById('game-stage').style.display = 'block'; nextPuzzle(); }
        function nextPuzzle() {
            let target = constellationData[Math.floor(Math.random()*constellationData.length)];
            const r = document.getElementById('game-renderer');
            r.innerHTML = `<h2 style="color:var(--nasa-yellow)">CHE COSTELLAZIONE È?</h2><canvas id="puzzle-canvas" width="300" height="250"></canvas><div id="p-choices" class="puzzle-choice-container"></div>`;
            const ctx = r.querySelector('canvas').getContext('2d');
            ctx.shadowBlur = 10; ctx.shadowColor = "#fff"; ctx.strokeStyle = "rgba(255,213,79,0.7)"; ctx.lineWidth = 3;
            target.lines.forEach(l => { ctx.beginPath(); ctx.moveTo(l[0],l[1]); ctx.lineTo(l[2],l[3]); ctx.stroke(); });
            ctx.fillStyle = "white"; target.stars.forEach(s => { ctx.beginPath(); ctx.arc(s[0],s[1],5,0,Math.PI*2); ctx.fill(); });
            [target.name, "Draco", "Lira"].sort().forEach(n => {
                const b = document.createElement('button'); b.className = 'btn-game'; b.innerText = n;
                b.onclick = () => {
                    const s = document.getElementById('game-stage');
                    if(n === target.name) { s.classList.add('blink-success'); setTimeout(() => { s.classList.remove('blink-success'); nextPuzzle(); }, 1200); }
                    else { s.classList.add('blink-error'); setTimeout(() => s.classList.remove('blink-error'), 600); }
                }; r.querySelector('#p-choices').appendChild(b);
            });
        }

        /* --- 4. OFFICINA (NUOVO HUB DOWNLOAD) --- */
        function loadWorkshop() {
            document.getElementById('hub-home').style.display = 'none';
            document.getElementById('game-stage').style.display = 'block';
            const r = document.getElementById('game-renderer');
            r.innerHTML = `
                <div style="padding:40px;">
                    <h2 style="color:var(--nasa-yellow)">🛠️ OFFICINA STELLARE</h2>
                    <p>Scarica i kit. Stampa, ritaglia e costruisci!</p>
                    
                    <div class="workshop-container">
                        <div class="workshop-card">
                            <div style="font-size:40px; margin-bottom:10px;">🎲</div>
                            <h3>Dado Lunare</h3>
                            <p style="font-size:0.9em; opacity:0.8;">Un modello 3D per imparare tutte le fasi della Luna.</p>
                            <br>
                            <a href="<?php echo plugin_dir_url(__FILE__); ?>files/dado-lunare.pdf" target="_blank" class="btn-game" style="text-decoration:none; display:inline-block;">
                                📥 SCARICA PDF
                            </a>
                        </div>

                        <div class="workshop-card">
                            <div style="font-size:40px; margin-bottom:10px;">☀️</div>
                            <h3>Orologio Solare</h3>
                            <p style="font-size:0.9em; opacity:0.8;">Uno gnomone tascabile per leggere l'ora con il Sole.</p>
                            <br>
                            <a href="<?php echo plugin_dir_url(__FILE__); ?>files/orologio-solare.pdf" target="_blank" class="btn-game" style="text-decoration:none; display:inline-block;">
                                📥 SCARICA PDF
                            </a>
                        </div>
                    </div>
                </div>`;
        }
    </script>
    <?php
    return ob_get_clean();
});