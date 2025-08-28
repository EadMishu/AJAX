<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 800px;">
    <div class="modal-content" style="height: 600px;">
      
      <!-- Modal Header -->
      <div class="modal-header">
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
          <button class="nav-link active" id="nav-Files-tab" data-bs-toggle="tab" data-bs-target="#nav-Files" type="button" role="tab" aria-controls="nav-Files" aria-selected="true">Select File</button>
          <button class="nav-link" id="nav-upload-tab" data-bs-toggle="tab" data-bs-target="#nav-upload" type="button" role="tab" aria-controls="nav-upload" aria-selected="false">Upload new</button>
        </div>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <div class="tab-content" id="nav-tabContent">
          
          <!-- Files Tab -->
          <div class="tab-pane fade show active" id="nav-Files" role="tabpanel" aria-labelledby="nav-Files-tab">
            <div class="card mb-3">
              <div class="card-header"><h5>All uploaded Files</h5></div>
              <div class="card-body" id="filesContainer">
                <!-- Files will be loaded here by Ajax -->
                <p>Loading files...</p>
              </div>
            </div>
          </div>

          <!-- Upload Tab -->
          <div class="tab-pane fade" id="nav-upload" role="tabpanel" aria-labelledby="nav-upload-tab">
            <div class="card mb-3">
              <div class="card-header">Upload new file</div>
              <div class="card-body">
                <div class="mb-3">
                  Drop files here, paste or
                  <button type="button" class="btn btn-link" id="browseBtn">Browse</button>
                  <input type="file" id="hiddenFileInput" style="display: none;" accept="image/*">
                  <div class="mt-3">
                    <img id="modalImagePreview" src="#" alt="Preview" style="display:none; width: 100%; max-height: 200px; object-fit: cover;">
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addImageBtn" data-bs-dismiss="modal">Add</button>
      </div>

    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Load files when the modal opens
    const modal = document.getElementById('exampleModal');
    if (modal) {
      modal.addEventListener('shown.bs.modal', function () {
        loadUploadedFiles();
      });
    }

    // Reload files when the "Select File" tab is activated
    const filesTab = document.getElementById('nav-Files-tab');
    if (filesTab) {
      filesTab.addEventListener('shown.bs.tab', function () {
        loadUploadedFiles();
      });
    }

    // Browse button triggers hidden file input
    const browseBtn = document.getElementById('browseBtn');
    const hiddenInput = document.getElementById('hiddenFileInput');
    const preview = document.getElementById('modalImagePreview');

    if (browseBtn && hiddenInput) {
      browseBtn.addEventListener('click', function() {
        hiddenInput.click();
      });

      hiddenInput.addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (file && file.type.startsWith('image/')) {
          const reader = new FileReader();
          reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
          };
          reader.readAsDataURL(file);
        } else {
          preview.src = '#';
          preview.style.display = 'none';
        }
      });
    }
  });

  // Function to load uploaded files from server
  function loadUploadedFiles() {
    const container = document.getElementById('filesContainer');
    if (!container) return;

    container.innerHTML = '<p>Loading files...</p>';

    fetch('/api/uploaded-files')  // Replace this with your actual API endpoint
      .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
      })
      .then(files => {
        if (!files || files.length === 0) {
          container.innerHTML = '<p>No files uploaded yet.</p>';
          return;
        }

        const ul = document.createElement('ul');
        ul.classList.add('list-group');

        files.forEach(file => {
          const li = document.createElement('li');
          li.classList.add('list-group-item');
          li.innerHTML = `
            <a href="${file.url}" target="_blank" rel="noopener noreferrer">${file.name}</a>
          `;
          ul.appendChild(li);
        });

        container.innerHTML = '';
        container.appendChild(ul);
      })
      .catch(error => {
        container.innerHTML = '<p class="text-danger">Failed to load files.</p>';
        console.error('Error loading files:', error);
      });
  }
</script>