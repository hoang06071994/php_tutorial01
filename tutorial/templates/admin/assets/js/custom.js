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
    })

    serviceSlug.addEventListener('change', (e) => {
        if (e.target.value.trim() == '') {
            sessionStorage.removeItem('save-slug')
            e.target.value = toSlug(serviceName.value)
        }
    })

    if (serviceSlug.value.trim() == '') {
        sessionStorage.removeItem('save-slug')
    }
}