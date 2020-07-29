<?php
/*
 * PHP Veritabanı sınıfı
 * 2011 - phpr.org
 */
/*
MySQL’e bağlanmak
MYSQL bağlantısı oluşturmak için öncelikle mysql_connect fonksiyonu ile veritabanı bilgileri girilir, sonra mysql_select_db fonksiyonu ile kullanılacak veritabanı seçilir. Hazırladığım bu sınıfta bu iki fonksiyon yerine daha sınıfı tanımlarken bağlantıyı yapmış oluyorsun.

$vt = new vt('kullanıcı', 'şifre', 'veritabanı');
Evet bu kadar basit. Bu satırdan sonrasında $vt değişkeni üzerinden tüm veritabanı işlemlerini gerçekleştirebiliriz. Eğer MySQL hostunuz localhost haricinde ise bunun için 4. parametreyi kullanabilirsin, bu parametre varsayılan olarak localhost tanımlı.

Veri eklemek
Bunun için 2 yöntem hazırladım. Birincisi ve benim önerdiğim yöntem eklenecek verileri array ile iletmek. Bu metodda ilk parametre her zaman tablo adı olacak.

$vt->ekle('uyeler', array('ad' => 'Musa', 'soyad' => 'Avcı'));
Güzel yanı çok fazla veri ekleyeceğimiz zaman düzenli oluyor.

$vt->ekle('siparisler', array(
  'adres' => $adres,
  'fiyat' => $fiyat,
  'urun' => $urun,
  'kargo' => $kargo,
  'tarih' => time()
));
Diğer yöntem ise eklenecek verinin her değerini ayrı bir parametre olarak girmek.

$vt->ekle('uyeler', 'ad=Musa', 'soyad=Avcı');
Bu yöntemde daha az değer içeren ve array() yazmaya değmeyecek kadar kısa eklemeler için tercih edilebilir.

Bu metod her iki yöntemde de cevap olarak mysql_insert_id() değerini yani yeni eklenen satırın ID’sini döndürür. Eğer eklenmez ise de olumsuz döner.

Veri düzenlemek
Bu metod içinde 2 yöntem mevcut ve yine mantığı eklemedeki olay gibi. 3 parametreli bu metodun ilk parametresi yine tablo adı, ikinci parametre düzenlenecek değerler, son parametre ise hangi verilerin düzenleneceğini belirtecek koşul.

$vt->duzenle('uyeler', array('ad' => 'Ahmet'), array('NO' => 15)));
$vt->duzenle('ozel', array('isim' => $isim,"linkler" => $link), array('id' => $id));
Bu ifade “uyeler” tablosunda NO’su 15 olan verinin “ad” değerini “Ahmet” olarak değiştir.

Her iki parametreye de birden fazla değer girerek koşul ya da düzenlenecek değer sayısını artırabilirsin.

$vt->duzenle('uyeler', 'ad="Mehmet"', 'NO=20');
Bu yöntemde eklemeden farklı olarak yazı değerleri tırnak içine almamız gerekiyor.

Veri silmek
Yine ilk parametrede tablo adı ve ikincide de koşulu giriyoruz, gayet basit. Eğer ikinci parametreyi yani koşulu girmezseniz MySQL “Truncate Table” komutunu çalıştırarak tüm tabloyu boşaltır.

$vt->sil('uyeler', array('NO' => 15));
// ya da 
$vt->sil('uyeler', 'NO=15');
Sorgu yapmak
Eğer yapmak istediğiniz düzenleme, silme ya da ekleme işlemi rutinin dışındaysa normal bir MySQL sorgusu çalıştırmak için sorgu metodunu kullanabilirsin.

$vt->sorgu('UPDATE haberler SET hit = hit + 1 WHERE NO = 37');
Tablo olarak verileri okumak
3 farklı veri okuma metodu hazırladım. Bunların başında tablo olarak çekmek var. Yani birden fazla değer içeren birden fazla içeriği göstermek için bu metodu rahatlıkla kullanabilirsin.

$uyeler = $vt->tablo('SELECT * FROM uyeler LIMIT 10');
 
foreach($uyeler as $uye) {
  echo $uye->NO . ' - ' . $uye->ad . ' ' . $uye->soyad . '<br/>';
}
Misalen ekranda şöyle bir çıktı oluşur:

1 - Musa Avcı
2 - Hakan Beşikçi
Tek satır veri okumak
Çekmek istediğiniz veri tek bir satırsa, örneğin sadece bir üyenin bilgisi gibi, bunun için satir metodunu kullanıyoruz. Kasaplardaki satır değil ha.

$uye = $vt->satir('SELECT * FROM uyeler WHERE NO = 1');
echo $uye->ad;
Ekranda 1 NO’lu üyenin adı yazar.

Tek bir değeri okumak
Diğer iki metoda göre en basit ve işlevsel okuma metodu bu diyebilirim. Çünkü tek bir değer okumaktan ziyade bir çok kontrol ya da sayaç işlerinde kullanılabiliyor.

echo $vt->veri('SELECT ad FROM uyeler WHERE NO = 1');
Ekranda direkt 1 NO’lu üyenin adı yazar. Bunun dışında giriş kontrol işlemlerinde ya da toplam verilere ulaşmak için kullanışı ise şöyle.

$var_mi = $vt->veri('SELECT COUNT(NO) FROM uyeler WHERE ad = "Musa"');
 
if($var_mi) {
  echo 'Musa adında bir üye var!';
} else {
  echo 'Musa adında bir üye yok.';
}
 
$toplam_uye = $vt->veri('SELECT COUNT(NO) FROM uyeler');
echo 'Toplam ' . $toplam_uye . ' adet üye var.';
Yaklaşık 100 satırlık bu sınıf ile temel olarak genel bir çok projenin rahatlıkla veritabanı yönetimi ihtiyacını karşılayacağına inanıyorum. 


*/
    class vt
    {
        public $sayac;
        private $baglanti;
        private $hataGoster = true;
        public $karekter_seti = 'utf8';

        function vt($kullanici, $sifre, $veritabani, $host = 'localhost')
        {			
            $this->baglanti = @mysqli_connect($host, $kullanici, $sifre) or die('MYSQL ile bağlantı kurulamadı');
            if($this->baglanti):
                mysqli_select_db($this->baglanti,$veritabani ) or die('( <b>'.$veritabani.'</b> ) isimli VERİTABANI BULUNAMADI');
                $this->sorgu('SET NAMES '.$this->karekter_seti);
            endif;
        }
        
        function sorgu($sorgu)
        {
            $sorgu = mysqli_query($this->baglanti,$sorgu);
            if(!$sorgu && $this->hataGoster)
                echo ('<p>HATA : <strong>'.mysqli_error($this->baglanti).'</strong></p>'); // bakalım deniyelim
            
            return $sorgu;
        }
        
        function ekle($tablo, $veriler)
        {
            if(is_array($veriler)):
                $alanlar = array_keys($veriler);
                $alan = implode(',', $alanlar); 
                $veri = '\''.implode("', '",array_map(array($this, 'tirnakKes'), $veriler)).'\'';
            else:
                $parametreler = func_get_args();
                $tablo = array_shift($parametreler);
                $alan = $veri = null;
                $toplamParametre = count($parametreler)-1;
                foreach($parametreler as $NO => $parametre):
                    $bol = explode('=', $parametre, 2);
                    if($toplamParametre == $NO):
                        $alan .= $bol[0];
                        $veri .= '\''.$this->tirnakKes($bol[1]).'\'';
                    else:
                        $alan .= $bol[0].',';
                        $veri .= '\''.$this->tirnakKes($bol[1]).'\',';                    
                    endif;
                endforeach;
            endif;
            
            $ekle = $this->sorgu('INSERT INTO '.$tablo.' ('.$alan.') VALUES ('.$veri.')');
            if($ekle)
                return mysqli_insert_id($this->baglanti);
        }
        
        function tablo($sorgu)
        {
			$tablo = $this->sorgu($sorgu);
			$sonuc = array();
			while($sonuclar = mysqli_fetch_object($tablo)){
				$sonuc[] = $sonuclar;
			}
			return $sonuc;
        }
        
        function satir($sorgu)
        {
            $satir = $this->sorgu($sorgu);
            if($satir)
                return mysqli_fetch_object($satir);
        }
        
        function veri($sorgu)
        {
            $veri = $this->sorgu($sorgu);
            if($veri):
                $sonuc = mysqli_fetch_array($veri);
                if(isset($sonuc[0])){
                return $sonuc[0];
            }
            endif;            
        }
        
        function sil($tablo, $kosul = null)
        {
            if($kosul):
                if(is_array($kosul)):
                    $kosullar = array();
                    foreach($kosul as $alan => $veri)
                        $kosullar[] = $alan.'=\''.$veri.'\'';
                endif;
                return $this->sorgu('DELETE FROM '.$tablo.' WHERE '.(is_array($kosul)?implode(' AND ',$kosullar):$kosul));
            else:
                return $this->sorgu('TRUNCATE TABLE '.$tablo);
            endif;
        }
        
        function duzenle($tablo, $deger, $kosul)
        {
            if(is_array($deger)):
                $degerler = array();
                foreach($deger as $alan => $veri)
                    $degerler[] = $alan."='".addslashes($veri)."'";
            endif;
            
            if(is_array($kosul)):
                $kosullar = array();
                foreach($kosul as $alan => $veri)
                    $kosullar[] = $alan."='".addslashes($veri)."'";
            endif;
            
            return $this->sorgu('UPDATE '.$tablo.' SET '.(is_array($deger) ? implode(',',$degerler):$deger).' WHERE '.(is_array($kosul)?implode(' AND ',$kosullar):$kosul));
        }
        
        function tirnakKes($veri)
        {
            if(!get_magic_quotes_gpc())
                return mysqli_real_escape_string($this->baglanti, $veri);
                
            return $veri;
        }
    }
?>