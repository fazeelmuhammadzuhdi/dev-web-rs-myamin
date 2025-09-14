  <div class="heading-block fancy-title border-bottom-0 title-bottom-border mt-4">
      <h4><span class="text-black">Link</span></h4>
  </div>

  <div class="grid-inner row gutter-20">
      <div class="col-auto">
          <?php foreach ($banner as $item) : ?>
              <a class="entry-image" href="<?= $item['link'] ?>">
                  <img src="<?= base_url('banner/' . $item['gambar']); ?>" alt="Image">
              </a>
          <?php endforeach; ?>


      </div>

  </div>