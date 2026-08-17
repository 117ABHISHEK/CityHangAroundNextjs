
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- GrapesJS CSS -->
    <link href="https://unpkg.com/grapesjs@0.17.25/dist/css/grapes.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .editor-container {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            background-color: #ffffff;
            padding: 1rem;
            height: 600px;
            overflow: auto;
        }
        .gjs-block:hover {
            background-color: #f1f3f5;
            cursor: pointer;
        }
    </style>
<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Edit Email Template</h4>
        </div>
        <div class="card-body">
            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="email-template-form" action="{{ route('admin.campaign_templates.update', $template->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Template Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Template Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter Template Name" value="{{ old('name', $template->name) }}" required>
                    @error('name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Hidden Inputs -->
                <input type="hidden" name="html_content" id="html_content">
                <input type="hidden" name="updated_by" value="{{ auth()->user()->id }}">

                <!-- GrapesJS Editor -->
                <div id="editor" class="editor-container mb-3"></div>

                <button type="submit" class="btn btn-success">Update Template</button>
            </form>
        </div>
    </div>
</div>
    <!-- GrapesJS JS -->
    <script src="https://unpkg.com/grapesjs@0.17.25/dist/grapes.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editor = grapesjs.init({
                container: '#editor',
                height: '500px',
                fromElement: false,
                storageManager: false,
                blockManager: {
                    appendTo: '#editor',
                    blocks: [
                        {
                            id: 'section',
                            label: 'Section',
                            content: '<section class="py-3"><h1>Your content here</h1></section>',
                            category: 'Basic',
                        },
                        {
                            id: 'text',
                            label: 'Text Block',
                            content: '<div><p>Your text here</p></div>',
                            category: 'Basic',
                        },
                        {
                            id: 'image',
                            label: 'Image Block',
                            content: '<div><img src="https://via.placeholder.com/150" alt="Image" class="img-fluid"/></div>',
                            category: 'Basic',
                        },
                        {
                            id: 'button',
                            label: 'Button',
                            content: '<div><button class="btn btn-primary">Click Me</button></div>',
                            category: 'Basic',
                        },
                    ],
                },
            });

            // Load existing HTML content into the editor
            editor.setComponents(@json($template->html_content));

            // Populate hidden input with editor content on form submit
            document.getElementById('email-template-form').addEventListener('submit', function (e) {
                const htmlContent = editor.getHtml();
                if (!htmlContent.trim()) {
                    e.preventDefault();
                    alert("Please add some content to the template before submitting.");
                    return;
                }
                document.getElementById('html_content').value = htmlContent;
            });
        });
    </script>
