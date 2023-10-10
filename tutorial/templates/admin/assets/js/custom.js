function toSlug (title) {
    let slug = title.toLowerCase();
    
    // lọc dấu
    slug = slug.replace(/á|à|ả|ã|ạ|ă|ắ|ằ|ặ|ẳ|ẵ|â|ấ|ầ|ẩ|ậ|ẫ/gi, 'a')
    slug = slug.replace(/è|é|ẻ|ẽ|ẹ|ê|ề|ế|ể|ễ|ệ/gi, 'e')
    slug = slug.replace(/i|ì|í|ỉ|ĩ|ị/gi, 'i')
    slug = slug.replace(/ò|ó|õ|ỏ|ọ|ô|ồ|ố|ỗ|ổ|ộ|ơ|ờ|ớ|ỡ|ở|ợ/gi, 'o')
    slug = slug.replace(/ù|ú|ũ|ủ|ụ|ư|ừ|ứ|ữ|ử|ự/gi, 'u')
    slug = slug.replace(/ỳ|ý|ỹ|ỷ|ỵ/gi, 'y')
    slug = slug.replace(/đ/gi, 'd')

    // chuyển dấu cách (khoảng trắng) => -
    slug = slug.replace(/ /gi, '-')

    // Xóa các ký tự đặc biệt
    slug = slug.replace(/\`|\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\+|\=|\.|\/|\?|\<|\>|\'|\"|\:|\;|\,|_/gi, '')
    return slug
}

let renderLink = document.querySelector('.render-link')

if (renderLink != null) {
    var aTag = document.createElement('span')
    aTag.innerHTML = `<a href="${rootUrl}" target="_blank">${rootUrl}</a>`
    renderLink.appendChild(aTag)
}


const serviceName = document.querySelector('.service-name')
const serviceSlug = document.querySelector('.service-slug')

if (serviceName != null && serviceSlug != null) {

    serviceName.addEventListener('keyup', (e) => {
        if (!sessionStorage.getItem('save-slug')) {
            if (e.target.value != '') {
                serviceSlug.value = toSlug(e.target.value)
            }
        }
    })

    serviceName.addEventListener('change', () => {
        sessionStorage.setItem('save-slug', 1)

        let currentLink = rootUrl+'/'+prefixUrl+'/'+serviceSlug.value.trim()+'.html'
        renderLink.querySelector('span a').innerHTML = currentLink
        renderLink.querySelector('span a').href = currentLink
    })

    serviceSlug.addEventListener('change', (e) => {
        if (e.target.value.trim() == '') {
            sessionStorage.removeItem('save-slug')
            e.target.value = toSlug(serviceName.value)
        }

        let currentLink = rootUrl+'/'+prefixUrl+'/'+serviceSlug.value.trim()+'.html'
        renderLink.querySelector('span a').innerHTML = currentLink
        renderLink.querySelector('span a').href = currentLink
    })

    if (serviceSlug.value.trim() == '') {
        sessionStorage.removeItem('save-slug')
    }
}

// custom class ckeditor
let classTextarea = document.querySelectorAll('.editor')

if (classTextarea !== null) {
    classTextarea.forEach((item, index) => {
        item.id = 'editor_'+(index + 1)
        CKEDITOR.replace(item.id)
    })
}

// ClassicEditor
// .create( document.querySelector( '#editor' ) )
// .catch( error => {
//     console.error( error );
// } );

CKFinder.popup( {
    chooseFiles: true,
    width: 800,
    height: 600,
    onInit: function( finder ) {
    finder.on( 'files:choose', function( evt ) {
    let fileUrl = evt.data.files.first().getUrl();
    //Xử lý chèn link ảnh vào input
    } );
    finder.on( 'file:choose:resizedImage', function( evt ) {
    let fileUrl = evt.data.resizedUrl;
    //Xử lý chèn link ảnh vào input
    } );
    }
    } );