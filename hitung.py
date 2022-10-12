import sys
import pandas as pd
import numpy as np
import math
import os
from decimal import *

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
    return Decimal((abs((xr - xr_prev)/xr)*100))

# def hitung(xr, persamaan_xl, persamaan_xu, persamaan_xr,):
#     xr_prev = xr
#     xr = Decimal((xl+xu)/2)
#     f_xl = Decimal(eval(persamaan_xl))
#     f_xu = Decimal(eval(persamaan_xu))
#     f_xr = Decimal(eval(persamaan_xr))

#     if (i != 0):
#         ea = calculateEa(xr, xr_prev)

#     print_ea = ea
#     if (i == 0):
#         print_ea = '-'

#     row = [{'iterasi': i+1, 'xl': xl, 'xu': xu, 'xr': xr, 'f(xl)': f_xl, 'f(xu)': f_xu, 'f(xr)': f_xr, '|ea|%': print_ea}]
#     df = pd.DataFrame.from_dict(row)
#     result = pd.concat([result, df])

#     impact, new_xl, new_xu, new_xr = evaluate(xl, xu, xr, f_xl, f_xr)
#     if (impact == "xl"):
#         xl = xr
#     elif (impact == "xu"):
#         xu = xr
#     else:
#         root = new_xl
#         break

persamaan = sys.argv[1]
# tex_persamaan = os.system("converter.py " + persamaan)
angka_signifikan = int(sys.argv[2])

es = Decimal(0.5*10**(2-angka_signifikan))
ea = Decimal(100.0)

xl = Decimal(sys.argv[3])
xu = Decimal(sys.argv[4])
xr = Decimal(0.0)
root = Decimal(0.0)

stopMethod = int(sys.argv[5]) # 1 = Digit Signifikan, 2 = Max Iterasi, 3 = |f(xr)|<e
maxIter = int(sys.argv[6])
epsilon = Decimal(sys.argv[7])

persamaan_xl = persamaan.replace('x', 'xl')
persamaan_xu = persamaan.replace('x', 'xu')
persamaan_xr = persamaan.replace('x', 'xr')

f_xl = Decimal(eval(persamaan_xl))
f_xu = Decimal(eval(persamaan_xu))

f_xl_xu = Decimal(f_xl*f_xu)

result = pd.DataFrame()

absFxr = Decimal(100.0)

metodeStop = 'Angka Signifikan \(|\epsilon _a| \lt \epsilon _s\)<br>'

if (f_xl_xu < 0):
    i = 0
    if (stopMethod == 1):

        while ea > es:
            xr_prev = xr
            xr = Decimal((xl+xu)/2)
            f_xl = Decimal(eval(persamaan_xl))
            f_xu = Decimal(eval(persamaan_xu))
            f_xr = Decimal(eval(persamaan_xr))

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
    elif (stopMethod == 2):
        metodeStop = "Maksimum iterasi : " + str(maxIter) + " iterasi"
        while i < maxIter:
            xr_prev = xr
            xr = Decimal((xl+xu)/2)
            f_xl = Decimal(eval(persamaan_xl))
            f_xu = Decimal(eval(persamaan_xu))
            f_xr = Decimal(eval(persamaan_xr))

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
    else:
        
        metodeStop = '\(|f(xr) \lt \)' + str(epsilon) + '<br>'
        while absFxr >= epsilon:
            xr_prev = xr
            xr = Decimal((xl+xu)/2)
            f_xl = Decimal(eval(persamaan_xl))
            f_xu = Decimal(eval(persamaan_xu))
            f_xr = Decimal(eval(persamaan_xr))
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
