<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Email Template</title>

    <!-- GrapesJS CSS -->
    <link href="https://unpkg.com/grapesjs@0.17.25/dist/css/grapes.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://unpkg.com/grapesjs@0.17.25/dist/css/grapes.min.css" rel="stylesheet">
    </noscript>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        #editor {
            border: 1px solid #ccc;
            background-color: #fafafa;
            padding: 10px;
            border-radius: 5px;
            height: 500px;
            overflow: auto;
            position: relative;
            box-sizing: border-box;
        }

        .gjs-block:hover {
            background-color: #f7f7f7;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Create Email Template</h1>

    <!-- Display any general error message for the form -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="email-template-form" action="{{ route('admin.campaign_templates.store') }}" method="POST">
        @csrf <!-- CSRF Token -->

        <!-- Template Name Input Field with Error Display -->
        <div class="form-group">
            <label for="name">Template Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Template Name" value="{{ old('name') }}" required>

            <!-- Show the error message if validation fails for the name field -->
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Hidden Input for HTML Content -->
        <input type="hidden" name="html_content" id="html_content">

        <input type="hidden" name="created_by" value="{{ auth()->user()->id }}">


        <!-- GrapesJS Editor -->
        <div id="editor"></div>

        <button type="submit" class="btn btn-primary mt-3">Save Template</button>
    </form>
</div>

<!-- GrapesJS JS -->
<script src="https://unpkg.com/grapesjs@0.17.25/dist/grapes.min.js" defer></script>

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
                        content: '<section><h1>Your content here</h1></section>',
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
                        content: '<div><img src="https://via.placeholder.com/150" alt="Image" /></div>',
                        category: 'Basic',
                    },
                    {
                        id: 'button',
                        label: 'Button',
                        content: '<div><button>Click Me</button></div>',
                        category: 'Basic',
                    },
                ],
            },
        });

        // Ensure the blocks are appended correctly and the editor works
        console.log('Editor Initialized:', editor);

        // Form submit event to populate hidden field with HTML content from the editor
        document.getElementById('email-template-form').addEventListener('submit', function (e) {
            // Get HTML content from GrapesJS editor
            const htmlContent = editor.getHtml();

            // If content is empty, prevent form submission and show an alert
            if (!htmlContent.trim()) {
                e.preventDefault();
                alert("Please add some content to the template before submitting.");
                return;
            }

            // Populate the hidden html_content input with the editor's HTML content
            document.getElementById('html_content').value = htmlContent;

            // Optionally, you can disable the "unsaved changes" warning here by setting window.onbeforeunload = null;
        });
    });
</script>

</body>
</html>
