#!/usr/bin/env python
#-*- coding:utf-8 -*-

from PyQt4.QtCore import *
from PyQt4.QtGui import *
from PyQt4.QtWebKit import *

class browser(QWebView):
    def __init__(self, url, targetFile, parent=None):
        super(browser, self).__init__(parent)

        self.targetFile = targetFile

        self.settings().setAttribute(QWebSettings.PluginsEnabled, True)

        self.timerScreen = QTimer()
        self.timerScreen.setInterval(2000)
        self.timerScreen.setSingleShot(True)
        self.timerScreen.timeout.connect(self.takeScreenshot)

        # timeout if it takes more than 25 seconds to get a screenshot
        self.rendering = False
        self.timeoutTimer = QTimer()
        self.timeoutTimer.singleShot(25000, self.timeout)

        self.loadFinished.connect(self.timerScreen.start)
        self.load(QUrl(url))


    def timeout(self):
        if not self.rendering:

            if app is not None:
                print 'Timed out'

                app.quit()

                sys.exit()


    def takeScreenshot(self):
        self.rendering = True
        frame = self.page().mainFrame()

        # changed from 1024/768 to 1024/883 to accommodate FREE designs
        self.page().setViewportSize(QSize(1024, 883))

        image   = QImage(self.page().viewportSize(), QImage.Format_ARGB32)
        painter = QPainter(image)

        frame.render(painter)

        painter.end()
        image.save(self.targetFile)

        sys.exit()

if __name__ == "__main__":
    import  sys
    app  = QApplication(sys.argv)
    (url, targetFile) = sys.argv[1:3]
    main = browser(url, targetFile)
    app.exec_()

