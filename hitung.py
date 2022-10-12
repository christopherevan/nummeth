import sys
import pandas as pd
import numpy as np
import math

def evaluate(xl, xu, xr, f_xl, f_xr):
    impact = ""
    x = f_xl*f_xr
    if (x > 0):
        xl = xr
        impact = "xl"
    elif (x < 0):
        xu = xr
        impact = "xu"
    else:
        impact = "root"
    return impact, xl, xu, xr

def calculateEa(xr, xr_prev):
    return float((abs((xr - xr_prev)/xr)*100))

persamaan = sys.argv[1]
angka_signifikan = int(sys.argv[2])

es = float(0.5*10**(2-angka_signifikan))
ea = float(100.0)

xl = float(sys.argv[3])
xu = float(sys.argv[4])
xr = float(0.0)
root = float(0.0)

stopMethod = int(sys.argv[5]) # 1 = Digit Signifikan, 2 = Max Iterasi, 3 = |f(xr)|<e
maxIter = int(sys.argv[6])
epsilon = float(sys.argv[7])

persamaan_xl = persamaan.replace('x', 'xl')
persamaan_xu = persamaan.replace('x', 'xu')
persamaan_xr = persamaan.replace('x', 'xr')

f_xl = float(eval(persamaan_xl))
f_xu = float(eval(persamaan_xu))

f_xl_xu = float(f_xl*f_xu)

result = pd.DataFrame()

absFxr = float(100.0)

metodeStop = 'Angka Signifikan \(|\epsilon _a| \lt \epsilon _s\)<br>'

if (f_xl_xu < 0):
    i = 0
    # Digit Signifikan
    if (stopMethod == 1):
        while ea > es:
            xr_prev = xr
            xr = float((xl+xu)/2)
            f_xl = float(eval(persamaan_xl))
            f_xu = float(eval(persamaan_xu))
            f_xr = float(eval(persamaan_xr))

            if (i != 0):
                ea = calculateEa(xr, xr_prev)

            print_ea = ea
            if (i == 0):
                print_ea = '-'

            row = [{'iterasi': i+1, 'xl': xl, 'xu': xu, 'xr': xr, 'f(xl)': f_xl, 'f(xu)': f_xu, 'f(xr)': f_xr, '|ea|%': print_ea}]
            df = pd.DataFrame.from_dict(row)
            result = pd.concat([result, df])

            impact, new_xl, new_xu, new_xr = evaluate(xl, xu, xr, f_xl, f_xr)
            if (impact == "xl"):
                xl = xr
            elif (impact == "xu"):
                xu = xr
            else:
                root = new_xl
                break

            i += 1
        mystr = 'Angka Signifikan = ' + str(angka_signifikan) + ' angka signifikan'
        metodeStop += mystr
        
        if (ea < es):
            root = xr
    
    # Max Iterasi
    elif (stopMethod == 2):
        metodeStop = "Maksimum iterasi : " + str(maxIter) + " iterasi"
        while i < maxIter:
            xr_prev = xr
            xr = float((xl+xu)/2)
            f_xl = float(eval(persamaan_xl))
            f_xu = float(eval(persamaan_xu))
            f_xr = float(eval(persamaan_xr))

            if (i != 0):
                ea = calculateEa(xr, xr_prev)

            print_ea = ea
            if (i == 0):
                print_ea = '-'

            row = [{'iterasi': i+1, 'xl': xl, 'xu': xu, 'xr': xr, 'f(xl)': f_xl, 'f(xu)': f_xu, 'f(xr)': f_xr, '|ea|%': print_ea}]
            df = pd.DataFrame.from_dict(row)
            result = pd.concat([result, df])

            impact, new_xl, new_xu, new_xr = evaluate(xl, xu, xr, f_xl, f_xr)
            if (impact == "xl"):
                xl = xr
            elif (impact == "xu"):
                xu = xr
            else:
                root = new_xl
                break

            i += 1
        # if (ea < es):
        #     root = xr
    # |f(xr)|<e
    else:
        metodeStop = '\(|f(xr)| \lt \)' + str(epsilon) + '<br>'
        while absFxr >= epsilon:
            xr_prev = xr
            xr = float((xl+xu)/2)
            f_xl = float(eval(persamaan_xl))
            f_xu = float(eval(persamaan_xu))
            f_xr = float(eval(persamaan_xr))
            absFxr = abs(f_xr)

            if (i != 0):
                ea = calculateEa(xr, xr_prev)

            print_ea = ea
            if (i == 0):
                print_ea = '-'

            row = [{'iterasi': i+1, 'xl': xl, 'xu': xu, 'xr': xr, 'f(xl)': f_xl, 'f(xu)': f_xu, 'f(xr)': f_xr, '|ea|%': print_ea}]
            df = pd.DataFrame.from_dict(row)
            result = pd.concat([result, df])

            impact, new_xl, new_xu, new_xr = evaluate(xl, xu, xr, f_xl, f_xr)
            if (impact == "xl"):
                xl = xr
            elif (impact == "xu"):
                xu = xr
            else:
                root = new_xl
                break

            i += 1
        mystr = '|f(xr)| = ' + str(absFxr)
        metodeStop += mystr
    output = ""

    for idx, row in result.iterrows():
        output += "<tr><td>{}</td><td>{}</td><td>{}</td><td>{}</td><td>{}</td><td>{}</td><td>{}</td><td>{}</td></tr>".format(row['iterasi'], row['xl'], row['xu'], row['xr'], row['f(xl)'], row['f(xu)'], row['f(xr)'], row['|ea|%'])
        root = row['xr']
    
    output += "</table>"
    # Kalo mau ganti kalimat hasilnya dibawah
    output += "<p>Metode Stop: " + metodeStop + " </p>"
    output += "<p>Salah satu akar dari {} adalah {}</p>".format(persamaan, root)
    print(output)
else: 
    print("-- WARNING -- \(f(xl)\\times f(xu)\geqslant0\)")
