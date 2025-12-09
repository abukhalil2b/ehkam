<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <title>تحليل ديناميكي لمتعلمي القرآن الكريم — الربع الأول 2025</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    /* الأساسيات */
    :root{
      --bg:#f9fafb;
      --text:#111827;
      --muted:#6b7280;
      --card:#ffffff;
      --border:#e5e7eb;
      --primary:#22c55e;
      --primary-d:#16a34a;
      --accent:#06b6d4;
      --thead:#0f172a;
      --thead-text:#f9fafb;
      --shadow:0 4px 12px rgba(0,0,0,0.08);
      --shadow-soft:0 2px 8px rgba(0,0,0,0.06);
    }
    *{ box-sizing:border-box; }
    body { font-family:"Tajawal",system-ui, -apple-system, Segoe UI, Roboto, Arial; background:var(--bg); margin:0; color:var(--text); }
    header { background:linear-gradient(90deg,var(--primary),var(--accent)); color:#fff; padding:28px 16px; text-align:center; }
    h1 { margin:0; font-size:26px; }
    header p{ margin:6px 0 0; opacity:0.95; }
    .container { max-width:1150px; margin:auto; padding:16px; }
    .section { background:var(--card); padding:18px; margin-top:20px; border-radius:12px; box-shadow:var(--shadow-soft); }
    h2 { color:var(--thead); margin:0 0 12px; font-size:20px; }
    h3 { margin:16px 0 8px; font-size:16px; color:var(--thead); }
    ul { list-style:none; padding:0; margin:0; }
    li { margin:6px 0; }
    li::before { content:"✔ "; color:var(--primary); margin-left:6px; }

    /* الجداول */
    table { width:100%; border-collapse:collapse; margin-top:10px; box-shadow:var(--shadow); background:#fff; border-radius:10px; overflow:hidden; }
    th, td { border:1px solid var(--border); padding:10px; text-align:center; }
    th { background:var(--thead); color:var(--thead-text); font-weight:600; }
    tr:nth-child(even){ background:#f3f4f6; }

    /* النماذج والأزرار */
    .grid{ display:grid; gap:10px; grid-template-columns:repeat(6, minmax(0,1fr)); }
    .grid > *{ min-width:0; }
    .controls{ display:flex; flex-wrap:wrap; gap:10px; margin-top:10px; }
    input, select { width:100%; padding:8px; border:1px solid var(--border); border-radius:8px; background:#fff; }
    label{ font-size:13px; color:var(--muted); display:block; margin-bottom:4px; }
    button { background:var(--primary); color:white; border:none; padding:9px 14px; border-radius:8px; cursor:pointer; font-weight:600; }
    button.secondary{ background:#0ea5e9; }
    button.muted{ background:#64748b; }
    button.danger{ background:#ef4444; }
    button:hover { background:var(--primary-d); }
    button.secondary:hover{ background:#0284c7; }
    button.muted:hover{ background:#475569; }
    button.danger:hover{ background:#dc2626; }

    /* الرسوم البيانية */
    .chart-wrap{ width:100%; height:320px; margin-top:10px; }

    /* الفلاتر */
    .filters{ display:grid; gap:10px; grid-template-columns:repeat(4, minmax(0,1fr)); margin-top:10px; }

    /* الطباعة */
    @media print {
      header { background:#fff; color:#000; border-bottom:2px solid var(--border); }
      .section { box-shadow:none; border:1px solid var(--border); }
      .chart-wrap{ height:220px; }
      .controls, .filters, #actions { display:none; }
    }

    /* تجاوب */
    @media (max-width:900px){
      .grid{ grid-template-columns:repeat(2, minmax(0,1fr)); }
      .filters{ grid-template-columns:repeat(2, minmax(0,1fr)); }
    }
  </style>
</head>
<body>
  <header>
    <h1>تحليل ديناميكي لمتعلمي القرآن الكريم — الربع الأول 2025</h1>
    <p>الجداول والرسوم تُحسب تلقائيًا من بيانات الطلاب</p>
  </header>

  <div class="container">

    <!-- الفلاتر -->
    <div class="section" id="filters">
      <h2>فلاتر العرض</h2>
      <div class="filters">
        <div>
          <label for="filter-state">الولاية</label>
          <select id="filter-state">
            <option value="">الكل</option>
          </select>
        </div>
        <div>
          <label for="filter-period">الفترة</label>
          <select id="filter-period">
            <option value="">الكل</option>
            <option>صباحي</option>
            <option>مسائي</option>
          </select>
        </div>
        <div>
          <label for="filter-mode">طريقة التعليم</label>
          <select id="filter-mode">
            <option value="">الكل</option>
            <option>حضوري</option>
            <option>عن بُعد</option>
          </select>
        </div>
        <div>
          <label for="filter-gender">الجنس</label>
          <select id="filter-gender">
            <option value="">الكل</option>
            <option>ذكر</option>
            <option>أنثى</option>
          </select>
        </div>
      </div>
      <div class="controls" id="actions">
        <button class="secondary" onclick="compute()">تطبيق الفلاتر وإعادة الحساب</button>
        <button class="muted" onclick="resetFilters()">إعادة تعيين الفلاتر</button>
        <button onclick="exportCSV()">تصدير CSV</button>
      </div>
    </div>

    <!-- إدخال طالب جديد -->
    <div class="section">
      <h2>إضافة طالب جديد</h2>
      <div class="grid">
        <div>
          <label for="mode">طريقة التعليم</label>
          <select id="mode"><option>حضوري</option><option>عن بُعد</option></select>
        </div>
        <div>
          <label for="gender">الجنس</label>
          <select id="gender"><option>ذكر</option><option>أنثى</option></select>
        </div>
        <div>
          <label for="age">العمر</label>
          <input type="number" id="age" placeholder="العمر" min="4" max="90" />
        </div>
        <div>
          <label for="period">الفترة</label>
          <select id="period"><option>صباحي</option><option>مسائي</option></select>
        </div>
        <div>
          <label for="state">الولاية</label>
          <input type="text" id="state" placeholder="مثال: السيب" />
        </div>
        <div style="display:flex; align-items:end; gap:10px;">
          <button onclick="addStudent()">إضافة</button>
          <button class="muted" onclick="compute()">إعادة الحساب</button>
          <button class="danger" onclick="clearRaw()">تفريغ البيانات</button>
        </div>
      </div>
    </div>

    <!-- جدول إدخال بيانات الطلاب -->
    <div class="section">
      <h2>البيانات الخام</h2>
      <table id="raw" aria-label="البيانات الخام">
        <thead>
          <tr><th>طريقة التعليم</th><th>الجنس</th><th>العمر</th><th>الفترة</th><th>الولاية</th></tr>
        </thead>
        <tbody>
          <tr><td>حضوري</td><td>ذكر</td><td>6</td><td>صباحي</td><td>السيب</td></tr>
          <tr><td>حضوري</td><td>أنثى</td><td>7</td><td>صباحي</td><td>السيب</td></tr>
          <tr><td>عن بُعد</td><td>ذكر</td><td>12</td><td>مسائي</td><td>السويق</td></tr>
          <tr><td>حضوري</td><td>أنثى</td><td>15</td><td>مسائي</td><td>مسقط</td></tr>
          <tr><td>عن بُعد</td><td>ذكر</td><td>25</td><td>مسائي</td><td>مسقط</td></tr>
        </tbody>
      </table>
    </div>

    <!-- جدول 1: الفئة العمرية × الفترة -->
    <div class="section">
      <h2>١) الفئة العمرية × الفترة</h2>
      <table id="age-period">
        <thead><tr><th>الفئة العمرية</th><th>صباحي</th><th>مسائي</th></tr></thead>
        <tbody></tbody>
      </table>
      <h3>التحليل</h3>
      <ul id="analysis1"></ul>
      <h3>التوصيات</h3>
      <ul id="recommend1"></ul>
      <div class="chart-wrap"><canvas id="chart1"></canvas></div>
    </div>

    <!-- جدول 2: الجنس × الفترة -->
    <div class="section">
      <h2>٢) الجنس × الفترة</h2>
      <table id="gender-period">
        <thead><tr><th>الجنس</th><th>صباحي</th><th>مسائي</th></tr></thead>
        <tbody></tbody>
      </table>
      <h3>التحليل</h3>
      <ul id="analysis2"></ul>
      <h3>التوصيات</h3>
      <ul id="recommend2"></ul>
      <div class="chart-wrap"><canvas id="chart2"></canvas></div>
    </div>

    <!-- جدول 3: الولاية × طريقة التعليم -->
    <div class="section">
      <h2>٣) الولاية × طريقة التعليم</h2>
      <table id="state-mode">
        <thead><tr><th>الولاية</th><th>حضوري</th><th>عن بُعد</th></tr></thead>
        <tbody></tbody>
      </table>
      <h3>التحليل</h3>
      <ul id="analysis3"></ul>
      <h3>التوصيات</h3>
      <ul id="recommend3"></ul>
      <div class="chart-wrap"><canvas id="chart3"></canvas></div>
    </div>

  </div>

  <footer>
    لتصدير الصفحة كملف PDF: استخدم خاصية <b>طباعة</b> في المتصفح واختر "حفظ كملف PDF".
  </footer>

  <!-- مكتبة Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    // مراجع الرسوم لتدميرها قبل إعادة الإنشاء
    let chartAge=null, chartGender=null, chartState=null;

    // ثوابت القيم المقبولة
    const PERIODS = ["صباحي","مسائي"];
    const MODES   = ["حضوري","عن بُعد"];
    const GENDERS = ["ذكر","أنثى"];

    // أدوات مساعدة
    function norm(t){ return (t || "").trim(); }
    function categorizeAge(age){
      if(age>=4 && age<=6)  return "4–6";
      if(age>=7 && age<=18) return "7–18";
      if(age>=19 && age<=50) return "19–50";
      return "أخرى";
    }

    // تعبئة خيارات الولاية في الفلاتر من البيانات الحالية
    function refreshStateFilter(states){
      const sel = document.getElementById("filter-state");
      const current = sel.value;
      sel.innerHTML = '<option value="">الكل</option>';
      Array.from(states).sort().forEach(s=>{
        const opt = document.createElement("option");
        opt.value = s; opt.textContent = s;
        sel.appendChild(opt);
      });
      // الحفاظ على الاختيار السابق إن وُجد
      if(states.has(current)){ sel.value = current; }
    }

    // إضافة طالب جديد
    function addStudent(){
      const mode   = norm(document.getElementById("mode").value);
      const gender = norm(document.getElementById("gender").value);
      const ageStr = norm(document.getElementById("age").value);
      const period = norm(document.getElementById("period").value);
      const state  = norm(document.getElementById("state").value);

      const age = parseInt(ageStr,10);

      // تحقق المدخلات
      if(!state){ alert("أدخل الولاية."); return; }
      if(!Number.isFinite(age) || age<4 || age>90){ alert("أدخل عمرًا صحيحًا بين 4 و 90."); return; }
      if(!PERIODS.includes(period)){ alert("اختر الفترة: صباحي أو مسائي."); return; }
      if(!MODES.includes(mode)){ alert("اختر طريقة التعليم: حضوري أو عن بُعد."); return; }
      if(!GENDERS.includes(gender)){ alert("اختر الجنس: ذكر أو أنثى."); return; }

      const tbody = document.querySelector("#raw tbody");
      const tr = document.createElement("tr");
      tr.innerHTML = `<td>${mode}</td><td>${gender}</td><td>${age}</td><td>${period}</td><td>${state}</td>`;
      tbody.appendChild(tr);

      // تنظيف الحقول
      document.getElementById("age").value="";
      document.getElementById("state").value="";

      compute();
    }

    // تفريغ البيانات الخام
    function clearRaw(){
      if(!confirm("سيتم حذف جميع الصفوف في البيانات الخام. هل أنت متأكد؟")) return;
      document.querySelector("#raw tbody").innerHTML = "";
      compute();
    }

    // إعادة تعيين الفلاتر
    function resetFilters(){
      document.getElementById("filter-state").value="";
      document.getElementById("filter-period").value="";
      document.getElementById("filter-mode").value="";
      document.getElementById("filter-gender").value="";
      compute();
    }

    // تصدير CSV
    function exportCSV(){
      const rows = document.querySelectorAll("#raw tbody tr");
      const data = [["طريقة التعليم","الجنس","العمر","الفترة","الولاية"]];
      rows.forEach(r=>{
        const tds = r.querySelectorAll("td");
        data.push(Array.from(tds).map(td=>td.textContent));
      });
      const csv = data.map(row=>row.map(cell=>{
        // اقتباس الخلايا التي تحتوي فاصلة
        const c = String(cell);
        return c.includes(",") ? `"${c.replace(/"/g,'""')}"` : c;
      }).join(",")).join("\n");
      const blob = new Blob([csv], {type:"text/csv;charset=utf-8;"});
      const url = URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.href = url; a.download = "raw-students.csv";
      document.body.appendChild(a); a.click();
      document.body.removeChild(a); URL.revokeObjectURL(url);
    }

    // القراءة + تطبيق الفلاتر
    function getData(){
      const rows = document.querySelectorAll("#raw tbody tr");
      const all = Array.from(rows).map(r=>{
        const c = r.querySelectorAll("td");
        return {
          mode:   norm(c[0].textContent),
          gender: norm(c[1].textContent),
          age:    parseInt(norm(c[2].textContent),10),
          period: norm(c[3].textContent),
          state:  norm(c[4].textContent)
        };
      }).filter(d=> Number.isFinite(d.age));

      // تحديث قائمة الولايات في الفلاتر
      const stateSet = new Set(all.map(d=>d.state).filter(Boolean));
      refreshStateFilter(stateSet);

      // تطبيق الفلاتر
      const fState  = norm(document.getElementById("filter-state").value);
      const fPeriod = norm(document.getElementById("filter-period").value);
      const fMode   = norm(document.getElementById("filter-mode").value);
      const fGender = norm(document.getElementById("filter-gender").value);

      const filtered = all.filter(d=>{
        return (!fState  || d.state===fState) &&
               (!fPeriod || d.period===fPeriod) &&
               (!fMode   || d.mode===fMode) &&
               (!fGender || d.gender===fGender);
      });

      return filtered;
    }

    function compute(){
      const data = getData();

      // 1) الفئة العمرية × الفترة
      const ageCats = {"4–6":{صباحي:0,مسائي:0},"7–18":{صباحي:0,مسائي:0},"19–50":{صباحي:0,مسائي:0},"أخرى":{صباحي:0,مسائي:0}};
      data.forEach(d=>{
        const cat = categorizeAge(d.age);
        if(ageCats[cat] && PERIODS.includes(d.period)){
          ageCats[cat][d.period]++;
        }
      });

      const tbody1 = document.querySelector("#age-period tbody");
      tbody1.innerHTML = "";
      Object.entries(ageCats).forEach(([cat,obj])=>{
        const tr = document.createElement("tr");
        tr.innerHTML = `<td>${cat}</td><td>${obj["صباحي"]}</td><td>${obj["مسائي"]}</td>`;
        tbody1.appendChild(tr);
      });

      document.getElementById("analysis1").innerHTML =
        "<li>الفئة 4–6 تميل للصباح.</li><li>الفئة 7–18 موزعة وتميل للمساء.</li><li>الفئة 19–50 تتركز في المساء.</li>";
      document.getElementById("recommend1").innerHTML =
        "<li>زيادة محتوى صباحي للأطفال.</li><li>فتح فصول مسائية للفئة 7–18.</li><li>برامج مرنة للبالغين مساءً.</li>";

      if(chartAge) chartAge.destroy();
      chartAge = new Chart(document.getElementById("chart1"),{
        type:"bar",
        data:{
          labels:Object.keys(ageCats),
          datasets:[
            {label:"صباحي",data:Object.values(ageCats).map(o=>o["صباحي"]),backgroundColor: "#22c55e"},
            {label:"مسائي",data:Object.values(ageCats).map(o=>o["مسائي"]),backgroundColor: "#06b6d4"}
          ]
        },
        options:{ responsive:true, maintainAspectRatio:false }
      });

      // 2) الجنس × الفترة
      const genderCats = {"ذكر":{صباحي:0,مسائي:0},"أنثى":{صباحي:0,مسائي:0}};
      data.forEach(d=>{
        if(genderCats[d.gender] && PERIODS.includes(d.period)){
          genderCats[d.gender][d.period]++;
        }
      });

      const tbody2 = document.querySelector("#gender-period tbody");
      tbody2.innerHTML = "";
      Object.entries(genderCats).forEach(([g,obj])=>{
        const tr = document.createElement("tr");
        tr.innerHTML = `<td>${g}</td><td>${obj["صباحي"]}</td><td>${obj["مسائي"]}</td>`;
        tbody2.appendChild(tr);
      });

      document.getElementById("analysis2").innerHTML =
        "<li>الذكور يفضلون الفترة المسائية أكثر.</li><li>الإناث موزعات بين الفترتين مع ميل للمساء.</li>";
      document.getElementById("recommend2").innerHTML =
        "<li>تخصيص برامج مسائية للذكور.</li><li>توفير مرونة للإناث بين الفترتين.</li>";

      if(chartGender) chartGender.destroy();
      chartGender = new Chart(document.getElementById("chart2"),{
        type:"bar",
        data:{
          labels:Object.keys(genderCats),
          datasets:[
            {label:"صباحي",data:Object.values(genderCats).map(o=>o["صباحي"]),backgroundColor:"#22c55e"},
            {label:"مسائي",data:Object.values(genderCats).map(o=>o["مسائي"]),backgroundColor:"#06b6d4"}
          ]
        },
        options:{ responsive:true, maintainAspectRatio:false }
      });

      // 3) الولاية × طريقة التعليم
      const stateCats = {};
      data.forEach(d=>{
        const s = d.state || "غير محدد";
        if(!stateCats[s]) stateCats[s] = { "حضوري":0, "عن بُعد":0 };
        if(MODES.includes(d.mode)) stateCats[s][d.mode]++;
      });

      const tbody3 = document.querySelector("#state-mode tbody");
      tbody3.innerHTML = "";
      Object.entries(stateCats).forEach(([s,obj])=>{
        const tr = document.createElement("tr");
        tr.innerHTML = `<td>${s}</td><td>${obj["حضوري"]}</td><td>${obj["عن بُعد"]}</td>`;
        tbody3.appendChild(tr);
      });

      document.getElementById("analysis3").innerHTML =
        "<li>السيب لديها حضور قوي حضوري.</li><li>السويق يميل للتعليم عن بُعد.</li><li>مسقط فيها تنوع بين الطريقتين.</li>";
      document.getElementById("recommend3").innerHTML =
        "<li>تعزيز البنية التحتية للحضوري في السيب.</li><li>دعم منصات التعليم عن بُعد في السويق.</li><li>برامج مزدوجة في مسقط.</li>";

      if(chartState) chartState.destroy();
      chartState = new Chart(document.getElementById("chart3"),{
        type:"bar",
        data:{
          labels:Object.keys(stateCats),
          datasets:[
            {label:"حضوري",data:Object.values(stateCats).map(o=>o["حضوري"]),backgroundColor:"#22c55e"},
            {label:"عن بُعد",data:Object.values(stateCats).map(o=>o["عن بُعد"]),backgroundColor:"#06b6d4"}
          ]
        },
        options:{ responsive:true, maintainAspectRatio:false }
      });
    }

    // جاهزية الصفحة
    window.addEventListener("load", compute);
  </script>
</body>
</html>
