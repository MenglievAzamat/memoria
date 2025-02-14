export function chapterDivider(text) {
  let result = []

  if (text) {
    let words = text.split(/\s/g)
    let page = ''

    for (let index in words) {
      let length = result.length === 0 ? 500 : 534

      if (page.length + words[index].length <= length) {
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