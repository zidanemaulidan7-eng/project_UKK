<?php
require_once '../../config/auth.php'; require_once '../../config/koneksi.php'; role_diizinkan(['Karyawan/Kasir']);
$page_title='Transaksi Penjualan';
$pelanggan=$conn->query("SELECT id_pelanggan,nama_pelanggan FROM pelanggan ORDER BY nama_pelanggan");
$produk=$conn->query("SELECT id_produk,kode_produk,nama_produk,harga,stok FROM produk WHERE stok>0 ORDER BY nama_produk");
$produk_list=[]; while($p=$produk->fetch_assoc()) $produk_list[]=$p;
include '../../partials/header.php'; include '../../partials/sidebar.php';
?>
<div class="topbar"><h1>Transaksi Penjualan</h1></div>
<div class="panel form-panel">
<form method="post" action="simpan.php" id="transaksiForm">
<label>Pelanggan</label>
<select name="id_pelanggan" required>
<option value="">-- Pilih Pelanggan --</option>
<?php while($p=$pelanggan->fetch_assoc()): ?><option value="<?= $p['id_pelanggan'] ?>"><?= htmlspecialchars($p['nama_pelanggan']) ?></option><?php endwhile; ?>
</select>

<div class="table-head"><h2>Produk</h2><button type="button" class="btn" id="addRow">+ Tambah Produk</button></div>
<table id="items"><thead><tr><th>Produk</th><th>Harga</th><th>Stok</th><th>Jumlah</th><th>Subtotal</th><th>Aksi</th></tr></thead><tbody></tbody></table>
<div class="total-box">Total: <strong id="totalText">Rp 0</strong></div>
<button class="btn primary" type="submit">Simpan Transaksi</button>
<a class="btn" href="../dashboard.php">Batal</a>
</form>
</div>
<script>
const products = <?= json_encode($produk_list) ?>;
const tbody = document.querySelector('#items tbody');
const totalText = document.getElementById('totalText');

function rupiah(n){return 'Rp ' + Number(n).toLocaleString('id-ID');}
function updateTotal(){
  let total=0;
  tbody.querySelectorAll('tr').forEach(r=>{
    const sel=r.querySelector('.product'); const qty=r.querySelector('.qty');
    const opt=sel.options[sel.selectedIndex];
    const price=Number(opt?.dataset.price||0); const sub=price*(Number(qty.value)||0);
    r.querySelector('.price').textContent=rupiah(price);
    r.querySelector('.subtotal').textContent=rupiah(sub); total+=sub;
  });
  totalText.textContent=rupiah(total);
}
function addRow(){
  const tr=document.createElement('tr');
  tr.innerHTML=`<td><select name="id_produk[]" class="product" required><option value="">-- Pilih Produk --</option>${products.map(p=>`<option value="${p.id_produk}" data-price="${p.harga}" data-stock="${p.stok}">${p.kode_produk} - ${p.nama_produk}</option>`).join('')}</select></td>
  <td class="price">Rp 0</td><td class="stock">-</td><td><input type="number" name="jumlah[]" class="qty" min="1" value="1" required></td><td class="subtotal">Rp 0</td><td><button type="button" class="btn small danger remove">Hapus</button></td>`;
  tbody.appendChild(tr);
  const sel=tr.querySelector('.product');
  sel.addEventListener('change',()=>{tr.querySelector('.stock').textContent=sel.options[sel.selectedIndex]?.dataset.stock||'-'; updateTotal();});
  tr.querySelector('.qty').addEventListener('input',updateTotal);
  tr.querySelector('.remove').addEventListener('click',()=>{tr.remove();updateTotal();});
}
document.getElementById('addRow').addEventListener('click',addRow);
addRow();

document.getElementById('transaksiForm').addEventListener('submit',e=>{
  for(const r of tbody.querySelectorAll('tr')){
    const sel=r.querySelector('.product'); const qty=Number(r.querySelector('.qty').value);
    const stock=Number(sel.options[sel.selectedIndex]?.dataset.stock||0);
    if(!sel.value || qty<1 || qty>stock){e.preventDefault(); alert('Periksa produk dan jumlah. Jumlah tidak boleh melebihi stok.'); return;}
  }
});
</script>
<?php include '../../partials/footer.php'; ?>
