import typo from "ru-typo";

export function chapterDividerV2(text) {
  let lineLength = 35
  let pageLines = 18
  text = '    ' + text

  let nlSplit = text.split('\n')

  let lines = []
  let result = []
  let hyphens = []
  let tmp = ''
  let page = []

  if (text.length > 0) {
    for (let line of nlSplit) {
      line = line.replace('    ', '¡¡¡¡')

      if (line.length <= lineLength || line.includes('<img')) {
        lines.push(line)
      } else {
        let words = line.split(' ')

        for (let word of words) {
          if ([tmp, word].join(' ').length <= lineLength) {
            tmp = tmp.length === 0 ? tmp + ' ' + word : [tmp, word].join(' ')
          } else {
            word = typo(word, { hyphens: true })
            hyphens = word.split('­')

            let initialLength = hyphens.length

            if ([tmp, hyphens[0]].join(' ').length <= lineLength) {
              tmp += ' ' + hyphens.shift()

              while (hyphens.length > 0 && [tmp, hyphens[0]].join(' ').length <= lineLength) {
                tmp += hyphens.shift()
              }
            }

            if (initialLength !== hyphens.length) {
              tmp += '-'
            }

            lines.push(tmp.trim())
            tmp = hyphens.join('')
          }
        }

        lines.push(tmp.trim())
        tmp = ''
      }
    }

    let count = 1

    while (lines.length > 0) {
      if (lines[0].includes('<img')) {
        result.push(page)
        result.push([lines.shift()])
        page = []
        count = 1
      } else if (count <= pageLines) {
        page.push(lines.shift())
        count++
      } else {
        if (result.length === 0) {
            pageLines = pageLines + 1
        }

        result.push(page)
        page = []
        count = 1
      }
    }

    result.push(page)
  }

  result = result.map(r => r.map(rr => rr.replaceAll('¡¡¡¡', '&nbsp;&nbsp;&nbsp;&nbsp;')))
  result = result.map(r => r.map(rr => rr.includes('<img') ? [rr] : rr.split(' ')))

  return result
}

export function chapterDivider(text) {
  let result = []

  if (text) {
    let words = text.split(' ')
    let page = ''

    for (let index = 0; index < words.length; index++) {
      let length = result.length === 0 ? 510 : 544

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

export function hyphenate(text) {
  if (text.includes('<img')) {
    return text
  }

  let result = text ?? '';
  result = result.replaceAll('\n', '¡').replaceAll('  ', 'ºº')
  result = typo(result, {hyphens: true});
  result = result.split(' ');

  let hyphenated = []
  let tmp = ''

  for (let word of result) {
    let shards = word.match(/((?!­).){1,32}/g)

    if (shards) {
      for (let shard of shards) {
        if (tmp.length + shard.length <= 31) {
          tmp += shard
          continue
        }

        hyphenated.push(tmp += ((shard !== shards[0] ? '-' : '').replaceAll('¡', '<br>').replaceAll('ºº', '&nbsp;&nbsp;')))
        tmp = shard
      }

      tmp += ' '
    }
  }

  if (tmp.length !== 0) {
    hyphenated.push(tmp)
  }

  hyphenated = hyphenated.join('')
  hyphenated = hyphenated.replaceAll('¡', '<br>').replaceAll('ºº', '&nbsp;&nbsp;')

  return hyphenated
}