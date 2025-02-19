export function chapterDivider(text) {
  let result = []

  if (text) {
    let words = text.split(/\s/g)
    let page = ''

    for (let index = 0; index < words.length; index++) {
      let length = result.length === 0 ? 500 : 534

      if (words[index].includes('<img')) {
        result.push(page.trim())
        page = ''
        let imgTag = [words[index], words[++index], words[++index]].join(' ')
        result.push(imgTag)
      } else if (page.length + words[index].length <= length) {
        page += words[index] + ' '
      } else {
        result.push(page.trim())
        page = words[index] + ' '
      }
    }

    if (page.length !== 0) {
      result.push(page.trim())
    }
  }

  return result
}

export function pageFormatter(pages, title, offset) {
  let pagesFormatted = []

  for (let index in pages) {
    pagesFormatted.push({
      title: index == 0 ? title : null,
      text: pages[index],
      page: parseInt(offset) + parseInt(index) + 1,
    })
  }

  return pagesFormatted
}